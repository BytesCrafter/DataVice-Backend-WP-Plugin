# DataVice - WordPress Plugin

The DataVice for WordPress is a user management for your application or game users. This is an alternative to firebase which you can host your own.

### REST API Implementation

[USER RESTAPI DOCS](https://github.com/BytesCrafter/DataVice-WP-Plugin/documentation/USER)
[CONTACT RESTAPI DOCS](https://github.com/BytesCrafter/DataVice-WP-Plugin/documentation/CONTACT)
[LOCATION RESTAPI DOCS](https://github.com/BytesCrafter/DataVice-WP-Plugin/documentation/LOCATION)
[SETTINGS RESTAPI DOCS](https://github.com/BytesCrafter/DataVice-WP-Plugin/documentation/SETTINGS)

REQUEST:
- Method: POST
- Header: key=Content-Type, value=application/x-www-form-urlencoded
- Params: key=wpid (value=User ID), key=snky (value=Session Key), key=fn (value=First Name) 

RESPONSE:
- code: 'unknown' message: 'Please contact your administrator. Request Unknown!' data: null
- code: 'failed' message: 'Required fields cannot be empty.' data: null
- code: 'failed' message: 'Invalid value for type.' data: null
- code: 'failed' message: 'Please contact your administrator. ID not in valid format!' data: null
- code: 'failed' message: 'No results found!' data: null
- code: 'failed' message: 'User not found!' data: null
- code: 'failed' message: 'This name is already exist' data: null
- code: 'unknown' message: 'Please contact your administrator. Update of First name Failed' data: null


IF SUCCESS:
```
    {
        "status": "success",
        "message": "Data has been updated successfully"
    }
```




## Contributing

Please read [CONTRIBUTING](CONTRIBUTING) for details on our code of conduct, and the process for submitting pull requests to us.

## Versioning

We use [SemVer](http://semver.org/) for versioning. For the versions available, see the [tags on this repository](https://github.com/BytesCrafter/DataVice-WP-Plugin/tags). 

## Authors

* **Bytes Crafter** - *Initial work* - [Github](https://github.com/BytesCrafter/DataVice-WP-Plugin.git)

See also the list of [contributors](https://github.com/BytesCrafter/DataVice-WP-Plugin/graphs/contributors) who participated in this project.

## License

This project is licensed under the GNU GPL License - see the [LICENSE](LICENSE) file for details

## Acknowledgments

* WordPress.Org
* MariaDB Developer