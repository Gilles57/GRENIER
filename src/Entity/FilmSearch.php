<?php


namespace App\Entity;


use Doctrine\Common\Collections\ArrayCollection;

class FilmSearch
{
    private $code;
    private $title;
    private $productionYear;
    private $vu;

    /**
     * @var ArrayCollection
     */
   private $genres;

    public function __construct()
    {
        $this->genres = new ArrayCollection();
    }


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
     * @return FilmSearch
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
     * @return FilmSearch
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
     * @return FilmSearch
     */
    public function setVu($vu)
    {
        $this->vu = $vu;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getGenres(): ArrayCollection
    {
        return $this->genres;
    }

    /**
     * @param ArrayCollection $genres
     */
    public function setGenres(ArrayCollection $genres): void
    {
        $this->genres = $genres;
    }






}