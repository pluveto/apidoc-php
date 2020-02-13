<?php
/**
 * @api {get} /user/:id
 * @apiParam {Number} id Users unique ID.
 */

/**
 * @api {post} /user/
 * @apiName GetUser
 * @apiGroup User
 *
 * @apiParam {String} [firstname]       Optional Firstname of the User.
 * @apiParam {String} lastname          Mandatory Lastname.
 * @apiParam {String} country="DE"      Mandatory with default value "DE".
 * @apiParam {Number} [age=18]          Optional Age with default 18.
 *
 * @apiParam (Login) {String} pass      Only logged in users can post this.
 *                                      In generated documentation a separate
 *                                      "Login" Block will be generated.
 *
 * @apiParam {Object} [address]         Optional nested address object.
 * @apiParam {String} [address[street]] Optional street and number.
 * @apiParam {String} [address[zip]]    Optional zip code.
 * @apiParam {String} [address[city]]   Optional city.
 * 
 * @apiSuccess {String} firstname Firstname of the User.
 */