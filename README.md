PHPAX-RS
========

PHPAX-RS is a [JAX-RS](https://jax-rs-spec.java.net/) like framework for writing REST APIs in PHP. It does not support all JAX-RS features but it only tries to bring the style of writing of REST resources that is known in Java EE world into PHP.

Key features of PHPAX-RS are following:
- mapping of HTTP requests to PHP method using annotations with support for URL path templates
- easy serialization/deserialization of input/output data without writing code to convert them
- grouping REST resources using OOP classes


## How to install?

*TODO: using composer*

## How to use it?

### 1. Create a resource end point class

Class methods are annotated in order to provide information for binding of HTTP requests. HTTP method is indicated using `@GET`, `@POST`, `@PUT` or `@DELETE` annotations. Accepted or provided MINE types of request or responses bodies are defined using `@Consumes` and `@Produces` annotations. For removing of duplicate code, global MINE types may be defined using class annotations.

All methods are mapped to a path relative to the end point path that is specified during end point registration. Annotation `@Path` may be used for mapping of a method to subpath by defining path template of desired subpath. Path template may contain parameters denoted by `{ name [: regex] }`, e.g. `{myParam}`, `{id:\d+}`, `{id:[1-9][0-9]*}`. Name of param may contain only letters and optional regex is a common PHP regular expression. Regex can be use for parameter value validation and mapping specification. Value of parameter may ontain any character except for `/`. 

```php
/**
 * @Consumes(application/json)
 * @Produces(application/json)
 */
class ExampleEndpoint {
    
    /**
     * Data with which we manipulates.
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
    
    /**
     * @GET
     * @Path(/count)
     * @Produces(text/plain)
     */
    public function count() {
        return ResponseBuilder::ok(count(self::$data));
    }
    
}
```

### 2. Init framework, add serializators and map our end point to a path

```php
// init framework with full path at host
$phpaxrs = new \phpaxrs\PhpaxRs('/rest-api/test');
// add serializator for JSON
$phpaxrs->add_serializator('application/json', '\phpaxrs\serializator\JsonSerializator');
// add end point and map it to relative URL to base user
$phpaxrs->add_endpoint('/my-test-end-point');
```

### 3. Deploy and test

Using `curl` in bash test `get_all` method:
```bash
curl -i -X GET -H "Accept: application/json" "http://localhost/rest-api/test"
```
and output should be following:
`{"1":"Test","2":"Test 2"}`

## What are supported exchange data formats?

Just JSON for now, but you can write your custom serializator for any format you want. Its simple, just implement `\phpaxrs\serializator\ISerializator` interface and than register its class with supported MINE type using `add_serializator` method as show shown before.

## TODO list - what is not done yet?

- support for HTTP Accept header with stars (e.g. `*/*` or `text/*`)
- authorization&authentification
- produce/consume multiple accept/content types in single mapped class
- *many more, this framework is not finished yet :-(*
