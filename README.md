PHPAX-RS
========

[![Build Status](https://api.travis-ci.org/freenetis/phpaxrs.svg?branch=master)](https://travis-ci.org/freenetis/phpaxrs)

PHPAX-RS is a [JAX-RS](https://jax-rs-spec.java.net/) like framework for writing REST APIs in PHP. It does not support all JAX-RS features, it only tries to bring the style of writing of REST resources that is known in Java EE world into PHP.

Key features of PHPAX-RS are following:
- mapping of HTTP requests to PHP methods using annotations with support for URL path templates,
- easy serialization/deserialization of input/output data without writing code to convert them,
- grouping REST resources using OOP classes.

## How to install?

*TODO: using composer*

## How to use it?

### 1. Create a resource end point class

Class methods are annotated in order to provide information for binding of HTTP requests. HTTP method is indicated using `@GET`, `@POST`, `@PUT` or `@DELETE` annotation. Accepted or provided MIME types of request or responses bodies are defined using `@Consumes` and `@Produces` annotations. For removing of duplicate code, global MIME types may be defined using class annotations.

All methods are mapped to a path relative to the end point path that is specified during end point registration. Annotation `@Path` may be used for mapping of a method to a subpath by defining path template of desired subpath. Path template may contain parameters writter in following syntax: `{name[:regex]}`, e.g. `{myParam}`, `{id:\d+}`, `{id:[1-9][0-9]*}`. The param name may contain only letters and the optional regex is a common PHP regular expression. The regex can be use for parameter value validation and mapping restriction. Value of parameter may ontain any character except for `/` and must be matched agains regex if it is specified.

A method mapped by above instructions is called if it match the HTTP request path and accept/provide same data types as HTTP request headers accept and content-type specifies. If request was invoked with PUT or POST HTTP method than the first argument passed to the method is sended request body. No data conversion is required passed body data are already handled by serializator. Arguments that follows contains values matched agains path template parameters. For better orientation in code they should be named same as parameter names are. Rest of request variables may be obtain by common PHP utilities and procedures (e.g. query string using `$_GET` variable).

Responses may are formed from what your method returns. If it is an instance of `\phpaxrs\http\HttpResponse` than it is directly used as the response. If nothing is returned than no content HTTP message with code 204 is sended as the response. If some object is returned than it is seialized, set up as response body and send with with HTTP message OK (code 200). If an exception is thrown during method processing then error HTTP response with code 500 is send. If no suitable method for the request was found than one of 4xx codes is sended as response (e.g. not found - 404).
For easy building of `HttpResponse` the `ResponseBuilder` class may be used.


```php
/**
 * @Consumes(application/json)
 * @Produces(application/json)
 */
class ExampleEndpoint {
    
    /**
     * Data storage - commonly data are fetch from a DB.
     */
    private static $data = array(
        1 => 'Test',
        2 => 'Test 2'
    );
    
    /**
     * @GET
     */
    public function get_all() {
        return self::$data;
    }
    
    /**
     * @POST
     */
    public function add($data) {
        if (!isset($data['id']) || !isset($data['name'])) {
            return ResponseBuilder::bad_request();
        }
        self::$data[$data['id']] = $data['name'];
        return ResponseBuilder::ok();
    }
    
    /**
     * @PUT
     */
    public function edit($data) {
        if (!isset($data['id']) || !isset($data['name'])) {
            return ResponseBuilder::bad_request();
        }
        if (!array_key_exists($data['id'], self::$data)) {
            return ResponseBuilder::not_found();
        }
        self::$data[$data['id']] = $data['name'];
        return ResponseBuilder::ok();
    }
    
    /**
     * @GET
     * @Path(/{id:\d+})
     * @Produces(text/plain)
     */
    public function get_item($id) {
        if (array_key_exists($id, self::$data)) {
            return ResponseBuilder::ok(self::$data[$id]);
        }
        return ResponseBuilder::not_found();
    }
    
    /**
     * @DELETE
     * @Path(/{id})
     */
    public function delete_item($id) {
        if (array_key_exists($id, self::$data)) {
            unset(self::$data[$id]);
            return ResponseBuilder::ok();
        }
        return ResponseBuilder::not_found();
    }
    
}
```

### 2. Init framework, add serializators and map end points to a path

```php
// init framework with full path at host
$phpaxrs = new \phpaxrs\PhpaxRs('/rest-api/test');
// add serializator for JSON
$phpaxrs->add_serializator('application/json', '\phpaxrs\serializator\JsonSerializator');
// add end point and map it to relative URL to base user
$phpaxrs->add_endpoint('/my-test-end-point', 'ExampleEndpoint');
```

### 3. Deploy and test

Using `curl` in bash test `get_all` method:
```bash
curl -i -X GET -H "Accept: application/json" "http://localhost/rest-api/test/my-test-end-point"
```
and output should be following:
`{"1":"Test","2":"Test 2"}`

## What are supported exchange data formats?

Just JSON for now, but you can write your custom serializator for any format you want. Its simple, just implement `\phpaxrs\serializator\ISerializator` interface and than register its class together with supported MINE type using `add_serializator` method as show shown before.

## TODO list - what important is not done yet?

- produce/consume multiple accept/content types in single mapped method/class
