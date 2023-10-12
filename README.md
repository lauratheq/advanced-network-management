# Advanced Network Management

This plugin helps managing WordPress plugins accross a multisite network. It provides advanced visibility options and provides the possibility to force de-/activation.

![Shows the manage plugin link on the plugin overview in the network area](/wp-assets/screenshot-1.png?raw=true "Shows the manage plugin link on the plugin overview in the network area")
![The manage plugin screen](/wp-assets/screenshot-2.png?raw=true "The manage plugin screen")

## Installation

1. Ensure you have a working multisite
1. Clone this repository to `wp-content/plugins/advanced-network-management`
1. Head to Network Admin > Plugins
1. Network activate the Plugin "Advanced Plugin Management for Multisite"

## Contributing

### Contributor Code of Conduct

Please note that this project is adapting the [Contributor Code of Conduct](https://learn.wordpress.org/online-workshops/code-of-conduct/) from WordPress.org. By participating in this project you agree to abide by its terms.

### Basic Workflow

* Grab an issue
* Fork the project
* Add a branch with the number of your issue
* Develop your stuff
* Commit to your forked project
* Send a pull request to the main branch with all the details

Please make sure that you have [set up your user name and email address](https://git-scm.com/book/en/v2/Getting-Started-First-Time-Git-Setup) for use with Git. Strings such as `silly nick name <root@localhost>` look really stupid in the commit history of a project.

Due to time constraints, you may not always get a quick response. Please do not take delays personally and feel free to remind.

### Workflow Process

* Every new issue gets the label 'Request'
* After reviewing the issue it will move within the [project](https://github.com/users/lauratheq/projects/1/views/1) to the column "Backlog".
* The way every issue follows: Backlog (New) > Todo (Next thing to do) > Doing > Done (closed or merged)
* Every commit must be linked to the issue with following pattern: `#${ISSUENUMBER} - ${MESSAGE}`
* Every PR only contains one commit and one reference to a specific issue

### Coding Guidelines

* We are using the [WordPress Coding Standard](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/). You can use the `composer install && composer cs` to test it.
