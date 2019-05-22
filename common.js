/* Function      : isEmpty
 * Purpose       : Check for empty string value.
 * Compatibility : JavaScript 1.2 (whiteout the first line JavaScript 1.0),
 *                 ECMA-262
 */
function isEmpty(str) {
    s = String(str);
    return ( (s == null) || (s.length == 0) )
}

/* Function      : inArray
 * Purpose       : Check if specific value is into array.
 * Compatibility : JavaScript 1.1, ECMA-262
 */
function inArray(arr, val) {
    for ( var i = 0; i < arr.length; ++i )
        if ( arr[i] == val )
            return true;
    return false;
}

/* Function      : FocusFirst
 * Purpose       : Focus first (checkbox, file, password, radio or text) form
 *                 element in first document form.
 * Return        : True if an element was focused.
 * Compatibility : JavaScript 1.1, DOM Level 1
 */
function focusFirst() {
    var i = 0;
    // list of allowed for focus element types
    allowedTypes = new Array("checkbox", "file", "password", "radio", "text");
    while ( i < document.forms.length ) {
        var j = 0;
        while ( j < document.forms[i].elements.length ) {
            if ( inArray(allowedTypes, document.forms[i].elements[j].type) ) {
                document.forms[i].elements[j].focus();
                return true;
            }
            ++j;
        }
        ++i;
    }
    return false;
}

/* Function      : dec2hex
 * Purpose       : Convert decimal to hex.
 * Return        : If decimal is not a valid number returned value is
 *                 prepended with zeros until string length become 'strLen'.
 * Compatibility : JavaScript 1.0
 */
function dec2hex(decimal, strLen, uppercase) {
    var dec = parseInt(decimal);
    var hex = "";

    if ( !isNaN(dec) ) {
        hex = dec.toString(16);

        if ( uppercase == true )
            hex = hex.toUpperCase();
    }

    /* prepend zeros */
    while ( hex.length < strLen )
        hex = "0" + hex;

    return hex;
}

/* Function      : changeColor
 * Purpose       : Change color of an HTML DOM element.
 * Compatibility : DOM Level 1
 */
function changeColor(element, color) {
    if ( element ) {
        element.style.color = color;
    }

    return 0;
}

/* Function      : changeBgColor
 * Purpose       : Change background color of an HTML DOM element.
 * Compatibility : DOM Level 1
 */
function changeBgColor(element, red, green, blue) {
    var color = "";

    color += "#";
    color += dec2hex(red, 2, true);
    color += dec2hex(green, 2, true);
    color += dec2hex(blue, 2, true);

    if ( element ) {
        element.style.backgroundColor = color;
    }

    return 0;
}
