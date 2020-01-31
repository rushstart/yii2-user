<?php


namespace rushstart\user;


interface ClientInterface
{
    /**
     * @param string $id service id.
     */
    public function setId($id);

    /**
     * @return string service id
     */
    public function getId();

    /**
     * @return string service name.
     */
    public function getName();

    /**
     * @param string $name service name.
     */
    public function setName($name);

    /**
     * @return string service title.
     */
    public function getTitle();

    /**
     * @param string $title service title.
     */
    public function setTitle($title);

    /**
     * @return array list of user attributes
     */
    public function getUserAttributes();

    /**
     * @return array view options in format: optionName => optionValue
     */
    public function getViewOptions();
}