# i18n Transformation Tool Stuff

In this directory you will find some tooling intended to assist with converting
WXR documents into a default format that can be used for the translation
services.

In some posts, there is meta data that contains PHP serialized arrays and this
tool will assist in converting that into XML data instead.

## test-transform

This file will run the SerializedtoXML unserializer on a file. Use the example-metavalue.txt file

Usage: `./test-transform example-metavalue.txt`


## transform-files

This will batch process all files in specified input directory to unserialize
them. Modified files are output to specified output directory. If no output
directory is specified "output" will be used, the directory will be created if
it doesn't already exist. Output files will have the same name as their input
file counterpart.

Usage: `./transform-files input [output]`

## expand-for-languages

This script will create language specific versions of each file in specified
input directory. The base name of the output directory can be passed as the
second argument, if not specified "output" will be used. One directory is
created for each language (configured within script file) using the base output
as part of it's name. Using language code "es_MX" and base output name "output"
the resulting directory name will be "es_MX_output". Each file in specified
input directory will be copied into the new language specific directory.

Usage: `./expand-for-languages input [output]`

## reserialize-files

Use this script to reverse the "SerializedToXML" process. This script will run
the XML_To_Serialized convert_data_xml_to_serialized method to convert XML data
to a PHP serialized string. WXR files processed initially by "transform-files"
will be suitable for import into WordPress when processed by this script.

Usage: `./reserialize-files input [output]`


## wxr-split

This script will split 'inputfile.wxr' into multiple WXR files, each file will
contain only one post. The resulting files will named based on the post_id 
(ex. for post_id=5 the filename will be 5.wxr)

Usage: `./wxr-split inputfile.wxr [output]`

## Workflow

Here are the two processes for for handling translatable content. The following
filename(s) and directories will be used in this example:

* localhost-2015-10-01.wxr - WordPress export file
* input - directory with files to used as import for several scripts
* output - output directory for unserialization results
* serialized - output directory for reserialization results

### Export

The following is an example of the process for exporting content for translation:

1. Export content from WordPress install
  * This files should be place along side these scripts
  * This file will be name localhost-2015-10-01.wxr in this example
2. Run the wxr-split script `./wxr-split localhost-2015-10-01.wxr input`
3. Run the transform-files script `./transform-files input output`
4. (Optional) Run the expand-for-languages script `./expand-for-languages output output`
5. Package out files to send to translator
  * `zip -r output.zip output` if you want to zip just the output directory
  * `zip -r output.zip *_output` if you expanded for languages
6. Send output.zip to translator

### Import

The import process is similar fairly quick compared to the export process. Here 
is an example of the import process:

1. Re-serialize the contents of "input" directory `./reserialize-files output serialized`
  * **NOTE:** if you ran the "expand-for-languages" script you will need to pass
   each of the language directories as well as specify a separate "serialized"
   directory for each language (ex. for language code es_MX input directory is
   es_MX_output output (serialized) directory should be es_MX_serialized.)
2. Import resulting files into WordPress.

