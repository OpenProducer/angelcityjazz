<?php

namespace Google\AdsApi\AdManager\v202305;


/**
 * This file was generated from WSDL. DO NOT EDIT.
 */
class performSuggestedAdUnitActionResponse
{

    /**
     * @var \Google\AdsApi\AdManager\v202305\SuggestedAdUnitUpdateResult $rval
     */
    protected $rval = null;

    /**
     * @param \Google\AdsApi\AdManager\v202305\SuggestedAdUnitUpdateResult $rval
     */
    public function __construct($rval = null)
    {
      $this->rval = $rval;
    }

    /**
     * @return \Google\AdsApi\AdManager\v202305\SuggestedAdUnitUpdateResult
     */
    public function getRval()
    {
      return $this->rval;
    }

    /**
     * @param \Google\AdsApi\AdManager\v202305\SuggestedAdUnitUpdateResult $rval
     * @return \Google\AdsApi\AdManager\v202305\performSuggestedAdUnitActionResponse
     */
    public function setRval($rval)
    {
      $this->rval = $rval;
      return $this;
    }

}
