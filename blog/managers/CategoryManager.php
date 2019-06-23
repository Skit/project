<?php


namespace blog\managers;


use backend\forms\CategoriesForm;
use backend\models\Categories;
use blog\transfers\CategoryTransfer;
use yii\base\Model;

class CategoryManager
{
    private $categoryTransfer;

    /**
     * CategoryManager constructor.
     * @param CategoryTransfer $categoryTransfer
     */
    public function __construct(CategoryTransfer $categoryTransfer)
    {
        $this->categoryTransfer = $categoryTransfer;
    }

    /**
     * @param CategoriesForm|Model $form
     * @return Categories
     */
    public function create(CategoriesForm $form)
    {
        $category = Categories::create($form);
        $this->categoryTransfer->save($category);

        return $category;
    }

    /**
     * @param CategoriesForm|Model $form
     * @return Categories
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function edit(CategoriesForm $form)
    {
        $category = $this->categoryTransfer->byId($form->id);
        Categories::edit($form, $category);

        $this->categoryTransfer->update($category);

        return $category;
    }
}