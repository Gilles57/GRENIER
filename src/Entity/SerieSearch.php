<?php


namespace App\Entity;


use Doctrine\Common\Collections\ArrayCollection;

class SerieSearch
{
    private $code;
    private $title;
    private $productionYear;
    private $vu;

    /**
     * @var ArrayCollection
     */
   private $genres;

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     */
    public function setCode($code): void
    {
        $this->code = $code;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     * @return SerieSearch
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProductionYear()
    {
        return $this->productionYear;
    }

    /**
     * @param mixed $productionYear
     * @return SerieSearch
     */
    public function setProductionYear($productionYear)
    {
        $this->productionYear = $productionYear;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getVu()
    {
        return $this->vu;
    }

    /**
     * @param mixed $vu
     * @return SerieSearch
     */
    public function setVu($vu)
    {
        $this->vu = $vu;
        return $this;
    }






}