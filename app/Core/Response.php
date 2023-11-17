<?php

/* using const and static properties in a class can be useful for defining constants and static properties 
that are associated with the class itself rather than an instance of the class. 

Const
Purpose: Constants declared using const are class constants. They are associated with the class and can be 
accessed using the scope resolution operator (::) without creating an instance of the class.
Use Cases: Defining meaningful and easily readable constant values associated with the class.
Avoiding the need to create an instance of the class to access constant values.

static 
Purpose: static properties are associated with the class itself, not with instances of the class. They are 
shared among all instances of the class and can be accessed using the scope resolution operator (::).

Use Cases: Sharing data across all instances of the class. Storing information that is not specific to an 
instance but is related to the class itself.
*/
class Response
{
  const NOT_FOUND = 404;
  const FORBIDDEN = 403;
}