<?php

namespace Google\AdsApi\AdManager\v202305;


/**
 * This file was generated from WSDL. DO NOT EDIT.
 */
class updateActivitiesResponse
{

    /**
     * @var \Google\AdsApi\AdManager\v202305\Activity[] $rval
     */
    protected $rval = null;

    /**
     * @param \Google\AdsApi\AdManager\v202305\Activity[] $rval
     */
    public function __construct(array $rval = null)
    {
      $this->rval = $rval;
    }

    /**
     * @return \Google\AdsApi\AdManager\v202305\Activity[]
     */
    public function getRval()
    {
      return $this->rval;
    }

    /**
     * @param \Google\AdsApi\AdManager\v202305\Activity[]|null $rval
     * @return \Google\AdsApi\AdManager\v202305\updateActivitiesResponse
     */
    public function setRval(array $rval = null)
    {
      $this->rval = $rval;
      return $this;
    }

}
