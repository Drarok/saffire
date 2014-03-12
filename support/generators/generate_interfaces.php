<?php

/*
 * This script generates interface classes
 *
 * Usage: generate_interfaces.php <path/to/file.dat> <path/to/file.c>
 */

$yaml = yaml_parse_file($argv[1]);


// Write C file
$fp = fopen($argv[2], "w");

$header = <<< EOH
/*
 Copyright (c) 2012-2013, The Saffire Group
 All rights reserved.

 Redistribution and use in source and binary forms, with or without
 modification, are permitted provided that the following conditions are met:
     * Redistributions of source code must retain the above copyright
       notice, this list of conditions and the following disclaimer.
     * Redistributions in binary form must reproduce the above copyright
       notice, this list of conditions and the following disclaimer in the
       documentation and/or other materials provided with the distribution.
     * Neither the name of the Saffire Group the
       names of its contributors may be used to endorse or promote products
       derived from this software without specific prior written permission.

 THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
 ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 DISCLAIMED. IN NO EVENT SHALL COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY
 DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/

    /*
     * WARNING: THIS FILE IS AUTOGENERATED!
     */


#include <stdio.h>
#include "objects/object.h"


EOH;
fwrite($fp, $header);


foreach ($yaml['interfaces'] as $interface) {
    fwrite($fp, "static void object_".$interface['name']."_init(void) {\n");
    fwrite($fp, "    Object_".ucfirst(strtolower($interface['name']))."_struct.attributes = ht_create();\n");
    foreach ($interface['methods'] as $method) {
        fwrite($fp, "    object_add_internal_method((t_object *)&Object_".ucfirst(strtolower($interface['name']))."_struct, \"".$method."\", ATTRIB_METHOD_STATIC, ATTRIB_VISIBILITY_PUBLIC, NULL);\n");
    }
    fwrite($fp, "    vm_populate_builtins(\"".$interface['name']."\", (t_object *)&Object_".ucfirst(strtolower($interface['name']))."_struct);\n");
    fwrite($fp, "}\n");
    fwrite($fp, "\n");
    fwrite($fp, "static void object_".$interface['name']."_fini(void) {\n");
    fwrite($fp, "    object_free_internal_object((t_object *)&Object_".ucfirst(strtolower($interface['name']))."_struct);\n");
    fwrite($fp, "}\n");
    fwrite($fp, "\n");
    fwrite($fp, "t_interface_object Object_".ucfirst(strtolower($interface['name']))."_struct = {\n");
    fwrite($fp, "    OBJECT_HEAD_INIT(\"".ucfirst(strtolower($interface['name']))."\", objectTypeUser, OBJECT_TYPE_INTERFACE|OBJECT_FLAG_IMMUTABLE, NULL, 0),\n");
    fwrite($fp, "};\n");
    fwrite($fp, "\n");
    fwrite($fp, "\n");
    fwrite($fp, "\n");
}

fwrite($fp, "\n\n");

fwrite($fp, "void object_interfaces_init(void) {\n");
foreach ($yaml['interfaces'] as $interface) {
    fwrite($fp, "    object_${interface['name']}_init();\n");
}
fwrite($fp, "}\n");
fwrite($fp, "\n");

fwrite($fp, "void object_interfaces_fini(void) {\n");
foreach (array_reverse($yaml['interfaces']) as $interface) {
    fwrite($fp, "    object_${interface['name']}_fini();\n");
}
fwrite($fp, "}\n");

fwrite($fp, "\n\n");


fclose($fp);





/*
 * Write H file
 */

$fp = fopen($argv[3], "w");

$header = <<< EOH
/*
 Copyright (c) 2012-2013, The Saffire Group
 All rights reserved.

 Redistribution and use in source and binary forms, with or without
 modification, are permitted provided that the following conditions are met:
     * Redistributions of source code must retain the above copyright
       notice, this list of conditions and the following disclaimer.
     * Redistributions in binary form must reproduce the above copyright
       notice, this list of conditions and the following disclaimer in the
       documentation and/or other materials provided with the distribution.
     * Neither the name of the Saffire Group the
       names of its contributors may be used to endorse or promote products
       derived from this software without specific prior written permission.

 THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
 ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 DISCLAIMED. IN NO EVENT SHALL COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY
 DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/
#ifndef ___GENERATED_INTERFACES_H__
#define ___GENERATED_INTERFACES_H__


    /*
     * WARNING: THIS FILE IS AUTOGENERATED!
     */

    typedef struct {
        SAFFIRE_OBJECT_HEADER;
    } t_interface_object;


EOH;
fwrite($fp, $header);

foreach ($yaml['interfaces'] as $interface) {
    fwrite($fp, "    // ${interface['name']}\n");
    fwrite($fp, "    t_interface_object Object_".ucfirst(strtolower($interface['name']))."_struct;\n");
    fwrite($fp, "    #define Object_".ucfirst(strtolower($interface['name']))." ((t_object *)&Object_".ucfirst(strtolower($interface['name']))."_struct)\n");
    fwrite($fp, "\n");
}

$footer = <<< EOT
#endif
EOT;
fwrite($fp, $footer);

fclose($fp);
