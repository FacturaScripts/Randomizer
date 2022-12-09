<?php
/**
 * This file is part of Randomizer plugin for FacturaScripts
 * Copyright (C) 2021 Carlos Garcia Gomez <carlos@facturascripts.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

namespace FacturaScripts\Plugins\Randomizer\Lib\Random;

use FacturaScripts\Core\Base\DataBase\DataBaseWhere;
use FacturaScripts\Core\Model\ProductoImagen;
use FacturaScripts\Dinamic\Model\AttachedFile;
use FacturaScripts\Dinamic\Model\Producto;
use FacturaScripts\Dinamic\Model\Variante;
use Faker;

/**
 * Description of Productos
 *
 * @author Carlos Garcia Gomez <carlos@facturascripts.com>
 * @author Jose Antonio Cuello <yopli2000@gmail.com>
 */
class Productos extends NewItems
{

    /**
     *
     * @param int $number
     *
     * @return int
     */
    public static function create(int $number = 50): int
    {
        $faker = Faker\Factory::create('es_ES');
        $maxCost = \mt_rand(0, 19) > 0 ? $faker->randomFloat(3, 0.01, 49) : $faker->numberBetween(1, 499);
        $maxPrice = \mt_rand(0, 19) > 0 ? $faker->randomFloat(3, 0.01, 99) : $faker->numberBetween(1, 1999);

        static::dataBase()->beginTransaction();
        for ($generated = 0; $generated < $number; $generated++) {
            $product = static::getProduct($faker);
            if ($product->exists()) {
                continue;
            }

            if (false === $product->save()) {
                break;
            }

            foreach ($product->getVariants() as $variant) {
                static::setVariantData($faker, $variant, $maxCost, $maxPrice);
                $variant->save();
            }

            $max = \mt_rand(-9, 9);
            $withAttr = \mt_rand(0, 4) === 0;
            while ($max > 0) {
                $variant = static::getNewVariant($faker, $product->idproducto);
                static::setVariantData($faker, $variant, $maxCost, $maxPrice);
                if ($withAttr) {
                    static::setVariantAttributes($variant);
                }
                $variant->save();
                $max--;
            }

            static::setImages($product);

            $product->loadFromCode($product->idproducto);
            $product->actualizado = $faker->dateTime();
            $product->save();
        }

        static::dataBase()->commit();
        return $generated;
    }

    /**
     *
     * @param Faker\Generator $faker
     *
     * @return Producto
     */
    private static function getProduct(&$faker)
    {
        $product = new Producto();
        $product->bloqueado = $faker->boolean(5);
        $product->codfabricante = static::codfabricante();
        $product->codfamilia = static::codfamilia();
        $product->codimpuesto = static::codimpuesto();
        $product->descripcion = $faker->paragraph;
        $product->fechaalta = $faker->date();
        $product->nostock = $faker->boolean(10);
        $product->observaciones = $faker->optional()->text(500);
        $product->publico = $faker->boolean();
        $product->referencia = static::newReferencia($faker);
        $product->secompra = $faker->boolean(90);
        $product->sevende = $faker->boolean(90);
        $product->ventasinstock = $faker->boolean();
        return $product;
    }

    /**
     *
     * @param Faker\Generator $faker
     * @param int             $idproduct
     *
     * @return Variante
     */
    private static function getNewVariant(&$faker, $idproduct)
    {
        $newVar = new Variante();
        $newVar->idproducto = $idproduct;
        $newVar->referencia = static::newReferencia($faker);
        return $newVar;
    }

    private static function getRandomImage(): string
    {
        // obtenemos una imagen aleatoria de la carpeta de imágenes
        $images = glob(FS_FOLDER . '/Plugins/Randomizer/Assets/Images/*');
        return $images[mt_rand(0, count($images) - 1)];
    }

    /**
     * 
     * @param Faker\Generator $faker
     *
     * @return string
     */
    private static function newReferencia($faker)
    {
        $option = \mt_rand(0, 9);
        switch ($option) {
            default:
                $ref = static::code(20);
                break;

            case 1:
                $ref = $faker->isbn10;
                break;

            case 2:
                $ref = $faker->isbn13;
                break;

            case 3:
                $ref = $faker->ean8;
                break;

            case 4:
                $ref = $faker->ean13;
                break;

            case 5:
                $ref = $faker->domainName;
                break;

            case 6:
                $ref = $faker->word . \mt_rand(1, 99999999);
                break;

            case 7:
                $ref = \mt_rand(1, 99999999) . $faker->word;
                break;
        }

        $variante = new Variante();
        $where = [new DataBaseWhere('referencia', $ref)];
        return $variante->loadFromCode('', $where) ? 'REF' . \mt_rand(1, 999999999) : $ref;
    }

    private static function setImageAttachedFile(string $filePath): AttachedFile
    {
        $fileName = basename($filePath);

        // buscamos si existe la imagen guardada en la biblioteca
        $file = new AttachedFile();
        $where = [new DataBaseWhere('filename', $fileName)];
        if ($file->loadFromCode('', $where)) {
            return $file;
        }

        // copiamos la imagen a la carpeta MyFiles
        if (false === copy($filePath, FS_FOLDER . '/MyFiles/' . $fileName)) {
            return $file;
        }

        // si no existe, guardamos la imagen en la biblioteca
        $file = new AttachedFile();
        $file->path = $fileName;
        $file->save();
        return $file;
    }

    private static function setImageProduct(int $idfile, int $idproducto, ?string $referencia = null)
    {
        $imageProducto = new ProductoImagen();
        $imageProducto->idfile = $idfile;
        $imageProducto->idproducto = $idproducto;
        $imageProducto->referencia = $referencia;
        $imageProducto->save();
    }

    private static function setImages(Producto $product)
    {
        // añadimos las imágenes del producto sin variantes
        $maxImgProduct = mt_rand(0, 3);
        for ($i = 0; $i < $maxImgProduct; $i++) {
            $filePath = static::getRandomImage();
            $file = static::setImageAttachedFile($filePath);
            if (false === $file->exists()) {
                continue;
            }
            static::setImageProduct($file->idfile, $product->idproducto);
        }

        // añadimos las imágenes de las variantes
        foreach ($product->getVariants() as $variant) {
            $maxImgVariant = mt_rand(0, 3);
            for ($i = 0; $i < $maxImgVariant; $i++) {
                $filePath = static::getRandomImage();
                $file = static::setImageAttachedFile($filePath);
                if (false === $file->exists()) {
                    continue;
                }
                static::setImageProduct($file->idfile, $product->idproducto, $variant->referencia);
            }
        }
    }

    /**
     * Assigns attribute values to a product variant.
     * Variants are assigned until a null is found or until a value is repeated
     * for an already defined attribute.
     *
     * @param Variante $variant
     */
    private static function setVariantAttributes(&$variant)
    {
        $attributes = [];
        for ($index = 1; $index < 5; $index++) {
            $value = static::atributo();
            if (null == $value || in_array($value->codatributo, $attributes)) {
                break;
            }
            $attributes[] = $value->codatributo;
            $variant->{'idatributovalor' . $index} = $value->id;
        }
    }

    /**
     *
     * @param Faker\Generator $faker
     * @param Variante        $variant
     * @param float           $maxCost
     * @param float           $maxPrice
     */
    private static function setVariantData(&$faker, &$variant, $maxCost, $maxPrice)
    {
        $variant->codbarras = $faker->optional(0.5)->ean13();
        $variant->coste = $faker->randomFloat(2, 0.1, $maxCost);
        $variant->margen = $faker->optional(0.2)->numberBetween(10, 100);
        $variant->precio = $faker->randomFloat(2, 0.1, $maxPrice);
    }
}
