<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Shop\Models;

use PHPUnit\Framework\TestCase;
use Modules\Shop\Models\Item as ItemModel;

class ItemModelTest extends TestCase
{
    /**
     * Tests that default values are null, empty, zero, or false.
     */
    public function testDefaultValues(): void
    {
        $model = new ItemModel();

        self::assertNull($model->getId());
        self::assertSame(0, $model->getCatId());
        self::assertSame('', $model->getName());
        self::assertSame('', $model->getCode());
        self::assertSame('', $model->getItemnumber());
        self::assertSame(0, $model->getStock());
        self::assertSame('', $model->getUnitName());
        self::assertSame(0, $model->getCordon());
        self::assertSame('', $model->getCordonText());
        self::assertNull($model->getCordonColor());
        self::assertSame('', $model->getPrice());
        self::assertSame(0, $model->getTax());
        self::assertSame('', $model->getShippingCosts());
        self::assertSame(0, $model->getShippingTime());
        self::assertSame('', $model->getImage());
        self::assertSame('', $model->getImage1());
        self::assertSame('', $model->getImage2());
        self::assertSame('', $model->getImage3());
        self::assertSame('', $model->getInfo());
        self::assertSame('', $model->getDesc());
        self::assertSame(0, $model->getStatus());
        self::assertFalse($model->isVariant());
        self::assertFalse($model->hasVariants());
    }

    /**
     * Tests that setId() sets the id.
     */
    public function testSetId(): void
    {
        $model = new ItemModel();
        $model->setId(5);

        self::assertSame(5, $model->getId());
    }

    /**
     * Tests that setId(0) stores zero.
     */
    public function testSetIdZero(): void
    {
        $model = new ItemModel();
        $model->setId(0);

        self::assertSame(0, $model->getId());
    }

    /**
     * Tests that setCatId() sets the category id.
     */
    public function testSetCatId(): void
    {
        $model = new ItemModel();
        $model->setCatId(2);

        self::assertSame(2, $model->getCatId());
    }

    /**
     * Tests that setName() sets the name.
     */
    public function testSetName(): void
    {
        $model = new ItemModel();
        $model->setName('T-Shirt Totenkopf');

        self::assertSame('T-Shirt Totenkopf', $model->getName());
    }

    /**
     * Tests that setCode() sets the code.
     */
    public function testSetCode(): void
    {
        $model = new ItemModel();
        $model->setCode('tshirttotenkopf_1587485020');

        self::assertSame('tshirttotenkopf_1587485020', $model->getCode());
    }

    /**
     * Tests that setItemnumber() sets the itemnumber.
     */
    public function testSetItemnumber(): void
    {
        $model = new ItemModel();
        $model->setItemnumber('0815nr1');

        self::assertSame('0815nr1', $model->getItemnumber());
    }

    /**
     * Tests that setStock() sets the stock.
     */
    public function testSetStock(): void
    {
        $model = new ItemModel();
        $model->setStock(14);

        self::assertSame(14, $model->getStock());
    }

    /**
     * Tests that setUnitName() sets the unit name.
     */
    public function testSetUnitName(): void
    {
        $model = new ItemModel();
        $model->setUnitName('Stück');

        self::assertSame('Stück', $model->getUnitName());
    }

    /**
     * Tests that setCordon() sets the cordon flag.
     */
    public function testSetCordon(): void
    {
        $model = new ItemModel();
        $model->setCordon(1);

        self::assertSame(1, $model->getCordon());
    }

    /**
     * Tests that setCordonText() sets the cordon text.
     */
    public function testSetCordonText(): void
    {
        $model = new ItemModel();
        $model->setCordonText('NEU');

        self::assertSame('NEU', $model->getCordonText());
    }

    /**
     * Tests that setCordonColor() sets the cordon color.
     */
    public function testSetCordonColor(): void
    {
        $model = new ItemModel();
        $model->setCordonColor('green');

        self::assertSame('green', $model->getCordonColor());
    }

    /**
     * Tests that setCordonColor() accepts null.
     */
    public function testSetCordonColorNull(): void
    {
        $model = new ItemModel();
        $model->setCordonColor(null);

        self::assertNull($model->getCordonColor());
    }

    /**
     * Tests that setPrice() sets the price.
     */
    public function testSetPrice(): void
    {
        $model = new ItemModel();
        $model->setPrice('25.00');

        self::assertSame('25.00', $model->getPrice());
    }

    /**
     * Tests that setTax() sets the tax.
     */
    public function testSetTax(): void
    {
        $model = new ItemModel();
        $model->setTax(19);

        self::assertSame(19, $model->getTax());
    }

    /**
     * Tests that setShippingCosts() sets the shipping costs.
     */
    public function testSetShippingCosts(): void
    {
        $model = new ItemModel();
        $model->setShippingCosts('0.00');

        self::assertSame('0.00', $model->getShippingCosts());
    }

    /**
     * Tests that setShippingTime() stores an int.
     */
    public function testSetShippingTime(): void
    {
        $model = new ItemModel();
        $model->setShippingTime('5');

        self::assertSame(5, $model->getShippingTime());
        self::assertIsInt($model->getShippingTime());
    }

    /**
     * Tests that setShippingTime() casts an empty string to zero.
     */
    public function testSetShippingTimeEmptyString(): void
    {
        $model = new ItemModel();
        $model->setShippingTime('');

        self::assertSame(0, $model->getShippingTime());
    }

    /**
     * Tests that setImage() sets the preview image.
     */
    public function testSetImage(): void
    {
        $model = new ItemModel();
        $model->setImage('application/modules/media/static/upload/image1.jpg');

        self::assertSame('application/modules/media/static/upload/image1.jpg', $model->getImage());
    }

    /**
     * Tests that setImage1() sets the first additional image.
     */
    public function testSetImage1(): void
    {
        $model = new ItemModel();
        $model->setImage1('application/modules/media/static/upload/image11.jpg');

        self::assertSame('application/modules/media/static/upload/image11.jpg', $model->getImage1());
    }

    /**
     * Tests that setImage2() sets the second additional image.
     */
    public function testSetImage2(): void
    {
        $model = new ItemModel();
        $model->setImage2('application/modules/media/static/upload/image12.jpg');

        self::assertSame('application/modules/media/static/upload/image12.jpg', $model->getImage2());
    }

    /**
     * Tests that setImage3() sets the third additional image.
     */
    public function testSetImage3(): void
    {
        $model = new ItemModel();
        $model->setImage3('application/modules/media/static/upload/image13.jpg');

        self::assertSame('application/modules/media/static/upload/image13.jpg', $model->getImage3());
    }

    /**
     * Tests that setInfo() sets the short info.
     */
    public function testSetInfo(): void
    {
        $model = new ItemModel();
        $model->setInfo('Produktinfo');

        self::assertSame('Produktinfo', $model->getInfo());
    }

    /**
     * Tests that setDesc() sets the description.
     */
    public function testSetDesc(): void
    {
        $model = new ItemModel();
        $model->setDesc('Produktbeschreibung');

        self::assertSame('Produktbeschreibung', $model->getDesc());
    }

    /**
     * Tests that setStatus() sets the status.
     */
    public function testSetStatus(): void
    {
        $model = new ItemModel();
        $model->setStatus(1);

        self::assertSame(1, $model->getStatus());
    }

    /**
     * Tests that setIsVariant() sets the variant flag.
     */
    public function testSetIsVariant(): void
    {
        $model = new ItemModel();
        $model->setIsVariant(true);

        self::assertTrue($model->isVariant());
    }

    /**
     * Tests that setHasVariants() sets the has variants flag.
     */
    public function testSetHasVariants(): void
    {
        $model = new ItemModel();
        $model->setHasVariants(true);

        self::assertTrue($model->hasVariants());
    }

    /**
     * Tests that Item setters are chainable.
     */
    public function testSettersReturnSelf(): void
    {
        $model = new ItemModel();

        self::assertSame($model, $model->setId(1));
        self::assertSame($model, $model->setCatId(2));
        self::assertSame($model, $model->setName('Name'));
        self::assertSame($model, $model->setCode('code'));
        self::assertSame($model, $model->setItemnumber('itemnumber'));
        self::assertSame($model, $model->setStock(3));
        self::assertSame($model, $model->setUnitName('unit'));
        self::assertSame($model, $model->setCordon(4));
        self::assertSame($model, $model->setCordonText('cordonText'));
        self::assertSame($model, $model->setCordonColor('cordonColor'));
        self::assertSame($model, $model->setPrice('price'));
        self::assertSame($model, $model->setTax(5));
        self::assertSame($model, $model->setShippingCosts('shippingCosts'));
        self::assertSame($model, $model->setShippingTime('6'));
        self::assertSame($model, $model->setImage('image'));
        self::assertSame($model, $model->setImage1('image1'));
        self::assertSame($model, $model->setImage2('image2'));
        self::assertSame($model, $model->setImage3('image3'));
        self::assertSame($model, $model->setInfo('info'));
        self::assertSame($model, $model->setDesc('desc'));
        self::assertSame($model, $model->setStatus(7));
        self::assertSame($model, $model->setIsVariant(true));
        self::assertSame($model, $model->setHasVariants(true));
    }

    /**
     * Tests that chaining setters builds a complete model.
     */
    public function testChainedSetters(): void
    {
        $model = (new ItemModel())
            ->setId(8)
            ->setCatId(9)
            ->setName('T-Shirt Beach')
            ->setCode('tshirtbeach_1587485058')
            ->setItemnumber('0815nr3')
            ->setStock(17)
            ->setUnitName('Stück')
            ->setCordon(0)
            ->setCordonText('')
            ->setCordonColor(null)
            ->setPrice('19.50')
            ->setTax(19)
            ->setShippingCosts('0.00')
            ->setShippingTime('5')
            ->setImage('application/modules/media/static/upload/muster-t-shirtbeach1.jpg')
            ->setImage1('application/modules/media/static/upload/muster-t-shirtbeach2.jpg')
            ->setImage2('')
            ->setImage3('')
            ->setInfo('Info')
            ->setDesc('Desc')
            ->setStatus(1)
            ->setIsVariant(false)
            ->setHasVariants(false);

        self::assertSame(8, $model->getId());
        self::assertSame(9, $model->getCatId());
        self::assertSame('T-Shirt Beach', $model->getName());
        self::assertSame('tshirtbeach_1587485058', $model->getCode());
        self::assertSame('0815nr3', $model->getItemnumber());
        self::assertSame(17, $model->getStock());
        self::assertSame('Stück', $model->getUnitName());
        self::assertSame(0, $model->getCordon());
        self::assertSame('', $model->getCordonText());
        self::assertNull($model->getCordonColor());
        self::assertSame('19.50', $model->getPrice());
        self::assertSame(19, $model->getTax());
        self::assertSame('0.00', $model->getShippingCosts());
        self::assertSame(5, $model->getShippingTime());
        self::assertSame('application/modules/media/static/upload/muster-t-shirtbeach1.jpg', $model->getImage());
        self::assertSame('application/modules/media/static/upload/muster-t-shirtbeach2.jpg', $model->getImage1());
        self::assertSame('', $model->getImage2());
        self::assertSame('', $model->getImage3());
        self::assertSame('Info', $model->getInfo());
        self::assertSame('Desc', $model->getDesc());
        self::assertSame(1, $model->getStatus());
        self::assertFalse($model->isVariant());
        self::assertFalse($model->hasVariants());
    }

    /**
     * Tests that overwriting previously set values works.
     */
    public function testOverwriteValues(): void
    {
        $model = new ItemModel();

        $model
            ->setId(1)
            ->setCatId(1)
            ->setName('Old Name')
            ->setCode('old-code')
            ->setItemnumber('old-itemnumber')
            ->setStock(1)
            ->setUnitName('old-unit')
            ->setCordon(1)
            ->setCordonText('old-cordon-text')
            ->setCordonColor('old-color')
            ->setPrice('10.00')
            ->setTax(19)
            ->setShippingCosts('10.00')
            ->setShippingTime('10')
            ->setImage('old-image.jpg')
            ->setImage1('old-image1.jpg')
            ->setImage2('old-image2.jpg')
            ->setImage3('old-image3.jpg')
            ->setInfo('old-info')
            ->setDesc('old-desc')
            ->setStatus(1)
            ->setIsVariant(true)
            ->setHasVariants(true);

        $model
            ->setId(2)
            ->setCatId(2)
            ->setName('New Name')
            ->setCode('new-code')
            ->setItemnumber('new-itemnumber')
            ->setStock(2)
            ->setUnitName('new-unit')
            ->setCordon(0)
            ->setCordonText('new-cordon-text')
            ->setCordonColor('new-color')
            ->setPrice('20.00')
            ->setTax(19)
            ->setShippingCosts('20.00')
            ->setShippingTime('20')
            ->setImage('new-image.jpg')
            ->setImage1('new-image1.jpg')
            ->setImage2('new-image2.jpg')
            ->setImage3('new-image3.jpg')
            ->setInfo('new-info')
            ->setDesc('new-desc')
            ->setStatus(0)
            ->setIsVariant(false)
            ->setHasVariants(false);

        self::assertSame(2, $model->getId());
        self::assertSame(2, $model->getCatId());
        self::assertSame('New Name', $model->getName());
        self::assertSame('new-code', $model->getCode());
        self::assertSame('new-itemnumber', $model->getItemnumber());
        self::assertSame(2, $model->getStock());
        self::assertSame('new-unit', $model->getUnitName());
        self::assertSame(0, $model->getCordon());
        self::assertSame('new-cordon-text', $model->getCordonText());
        self::assertSame('new-color', $model->getCordonColor());
        self::assertSame('20.00', $model->getPrice());
        self::assertSame(19, $model->getTax());
        self::assertSame('20.00', $model->getShippingCosts());
        self::assertSame(20, $model->getShippingTime());
        self::assertSame('new-image.jpg', $model->getImage());
        self::assertSame('new-image1.jpg', $model->getImage1());
        self::assertSame('new-image2.jpg', $model->getImage2());
        self::assertSame('new-image3.jpg', $model->getImage3());
        self::assertSame('new-info', $model->getInfo());
        self::assertSame('new-desc', $model->getDesc());
        self::assertSame(0, $model->getStatus());
        self::assertFalse($model->isVariant());
        self::assertFalse($model->hasVariants());
    }
}
