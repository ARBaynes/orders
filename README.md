# Simple Orders API

There are two endpoints for this API:

* ``/order/{order_id}``
    * This is to view the full details of a singular order.
   
* ``/orders/``
    * This endpoint displays the summarised details of every order.
    I followed the given requirements, although the response details for this endpoint can be easily
     changed in the future, if necessary.
    * I can see this endpoint may get a little unwieldy with the number of orders that Everpress has, so I would
    probably implement some kind of system that could process batches of orders concurrently, if I were to keep working on this.
    
