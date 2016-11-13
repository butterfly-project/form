CHANGELOG
=========

1.7.0
-----

Added functionality:

 * Added StrReplace, RegReplace transformer

1.6.0
-----

Added functionality:

 * Added LetterCase transformer
 * Added JsonEncode, JsonDecode transformer
 * Upgrade phpunit to version 5.5

1.5.0
-----

Added functionality:

 * Added structured error messages

1.4.0
-----

Added functionality:

 * Added isFiltered method in IConstraint

1.3.0
-----

Added functionality:

 * Added email validator

1.2.0
-----

Added functionality:

 * Added List constraint
 * Added filtering object in ArrayConstraint
 
Changes:
 * Updated IConstraint

1.1.0
-----

Changes:

 * Merge package with transform and validation packages
 * Renamed transformers: StringMaxLength to StringLength, StringTrim to Trim

Added functionality:

 * Added validators: ArrayContains, ArrayCount, ArrayHasKey, Composite, 
   InArray, Instance, IsEmpty, IsNotEmpty, RegExp, Type
 * Added transformers: Mapper, ToDateTime, ToType

1.0.5
-----

Changes:

 * Fix bug for modify constraints filter during

1.0.4
-----

Added functionality:

 * Added synthetic constraint

1.0.3
-----

Added functionality:

 * Added methods of save and restore values to ScalarConstraint 

1.0.2
-----

Added functionality:

 * Runtime edit constraint in Callable validator and transform 

1.0.1
-----

Added functionality:

 * Added getValue, getOldValue in IConstraint

1.0.0
-----

Added functionality:

 * Scalar constraint
 * Array constraint
