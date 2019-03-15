<?php
/**
 * Created by PhpStorm.
 * User: Start
 * Date: 03.11.2018
 * Time: 16:53
 */

namespace common\services\models;

use backend\models\PostsTags;
use backend\models\Tags;
use backend\forms\TagForm;
use common\exception\NotFoundException;
use yii\base\Model;

class TagsService extends TagServicesRepo
{
    /**
     * Выполняет все операции над тегами: добавление, удаление, повышение/уменьшение частоты использования,
     * добавляет связь тега и материала.
     * @param Model $model
     * @param string $oldTags
     * @throws \yii\db\Exception
     */
    public function operation(Model $model, string $oldTags)
    {
        // FIXME связи создает не корректно, frequency не уменьшает. Отсальное не проверено
        $old = $this->arrayFromString($oldTags);
        $new = $this->arrayFromString($model->tags);

        $deleteTags = $this->getDelete($new, $old);
        $addTags = $this->getAdded($new, $old);

        $existAdded = $this->getExistTags($addTags);
        $deleteTags = $this->getExistTags($deleteTags);

        $this->frequencyDown($deleteTags);
        $this->frequencyUp($existAdded);

        $create = $this->getToCreate($addTags, $existAdded);
        $this->batchCreate($create);

        $this->batchLink($model->id, $this->getAllByNames($addTags));
        $this->batchUnLink($model->id, $deleteTags);
    }

    /**
     * @param TagForm $form
     * @param bool $validate
     * @return Tags
     */
    public function createFromForm(TagForm $form, bool $validate=true): Tags
    {
        if($validate && ! $form->validate()) {
            throw new \RuntimeException('Data is not valid.');
        }

        return $this->create($form->name, $form->slug);
    }

    /**
     * @param array $tags
     * @return int
     * @throws \yii\db\Exception
     */
    public function batchCreate(array $tags): int
    {
        return Tags::batchCreate(
            $this->setTagsSlug($tags)
        );
    }

    /**
     * @param array $tags
     * @return array
     */
    public function frequencyUp(array $tags): array
    {
        return $this->frequency($tags, 1);
    }

    /**
     * @param array $tags
     * @return array
     */
    public function frequencyDown(array $tags): array
    {
        return $this->frequency($tags, -1);
    }

    /**
     * @param int $id
     * @param array $addTags
     * @return int
     */
    public function batchLink(int $id, array $addTags): int
    {
        if (empty($addTags)) {
            return 0;
        }

        return PostsTags::batchCreate($this->setTagsReference($id, $addTags));
    }

    /**
     * @param int $id
     * @param array $tags
     * @return int
     */
    public function batchUnlink(int $id, array $tags): int
    {
        if (empty($tags)) {
            return 0;
        }

        return PostsTags::batchDelete(
            $this->setTagsReference($id, $tags)
        );
    }

}