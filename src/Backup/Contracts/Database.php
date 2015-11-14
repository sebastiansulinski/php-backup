<?php namespace SSD\Backup\Contracts;

interface Database
{
    /**
     * Database type.
     *
     * @return string
     */
    public function type();

    /**
     * Set database host.
     *
     * @param $host
     * @return $this
     */
    public function setHost($host);

    /**
     * Set database name.
     *
     * @param $name
     * @return $this
     */
    public function setName($name);

    /**
     * Set database username.
     *
     * @param $user
     * @return $this
     */
    public function setUser($user);

    /**
     * Set database password.
     *
     * @param $password
     * @return $this
     */
    public function setPassword($password);

    /**
     * Set database port.
     *
     * @param $port
     * @return $this
     */
    public function setPort($port);

    /**
     * Set export file name.
     *
     * @param $name
     * @return $this
     */
    public function setFileName($name);

    /**
     * Get export file name.
     *
     * @return null|string
     */
    public function fileName();

    /**
     * Check if all properties have values.
     *
     * @return bool
     */
    public function isValid();
}