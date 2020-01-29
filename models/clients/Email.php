<?php


namespace rushstart\user\models\clients;


class Email extends BaseClient
{

    /**
     * email
     * @inheritDoc
     */
    public function setId($id)
    {
        $this->source_id = $id;
    }

    /**
     * email
     * @inheritDoc
     */
    public function getId()
    {
        return $this->source_id;
    }

    /**
     * email
     * @inheritDoc
     */
    public function getName()
    {
        // TODO: Implement getName() method.
    }

    /**
     * email
     * @inheritDoc
     */
    public function setName($name)
    {
        // TODO: Implement setName() method.
    }

    /**
     * Email
     * @inheritDoc
     */
    public function getTitle()
    {
        // TODO: Implement getTitle() method.
    }

    /**
     * Email
     * @inheritDoc
     */
    public function setTitle($title)
    {
        // TODO: Implement setTitle() method.
    }

    /**
     * @inheritDoc
     */
    public function getUserAttributes()
    {
        // TODO: Implement getUserAttributes() method.
    }

    /**
     * @inheritDoc
     */
    public function getViewOptions()
    {
        // TODO: Implement getViewOptions() method.
    }
}