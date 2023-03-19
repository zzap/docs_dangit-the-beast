# docs_dangit: A search engine for WordPress developers

Website: https://docs-dang.it/

[CloudFest 2023 Hackathon project](https://www.cloudfest.com/a-search-engine-for-wordpress-developers)

This repo contains the backend code for [the project website](https://docs-dang.it/). Code for the frontend is in [docs-dangit](https://github.com/zzap/docs-dangit/) repo.


## Usage

    # Install Composer dependencies
    composer install

    # Copy and customize the .ini file:
    cp config/config.ini.dist config/config.ini

    # List valid commands
    ./bin/docsdangit example

## Participants:
- Jessica Lyschik - https://github.com/luminuu - design
- Dhanuka Nuwan - https://github.com/dhanukanuwan - frontend
- Sven Wagener - https://github.com/mahype - parser/backend
- David Beja - https://github.com/dbeja - parser/backend
- Andreas Heigl - https://github.com/heiglandreas - data/storage
- Kevin Batdorf - https://github.com/KevinBatdorf - devOps/frontend
- Karsten Frohwein - https://github.com/kfrohwein - parser/backend
- Aleksandar Savković - https://profiles.wordpress.org/wpaleks/ - social/presentation
- Luke Carbis - https://github.com/lukecarbis - frontend
- Milana Cap - https://github.com/zzap - docs/DNS

## Sources

### Code reference

The Code reference handbook is partly generated from the code. Most of the code samples are in the User Contributed Notes section, which are comments ([example](https://developer.wordpress.org/reference/classes/wp_query/#user-contributed-notes)). 

URL: https://developer.wordpress.org/reference/

### WP-CLI 

We use wp cli cmd-dump to export all commands (including the examples) in a giant JSON file which is stored in the GitHub repo. GitHub action is doing this automatically once a day at 6AM.

URL: https://github.com/wp-cli/handbook/tree/main/commands 

## Technical solution

### Backend

Parsers are built on top of [Symfony Console](https://symfony.com/doc/current/components/console.html) component. At the moment of building the tool (CloudFest hackathon 2023), there are two parsers:
- [WordPress code reference](https://github.com/zzap/docs_dangit-the-beast/blob/main/src/Parsers/WordPress_Docs.php) 
- [WP-CLI](https://github.com/zzap/docs_dangit-the-beast/blob/main/src/Parsers/WP_CLI.php)

Storage is in mySQL with a full-text index and [a small API](https://github.com/zzap/docs_dangit-the-beast/tree/main/backend) built with [Laminas](https://docs.laminas.dev/) and [Mezzio](https://docs.mezzio.dev/) frameworks. API is private for now but could be made public in the future given the proper setup and storage financing is provided.

#### Data fields

- Code snippet - the snippet extracted from the source.
- Context - the whole data chunk, e.g. comment
- Parse date - the date source was parsed.
- URL - URL of the source.
- Code creator - author of the snippet (if available).
- Code creation datetime - the date of source creation (if available), e.g. comment date.
- Source - the source of the snippet, e.g. wpcli, wp-reference, wp-reference-comment etc. 
- Version - source version, e.g. WP 6.2, WP-CLI 2.7 etc.
- Command tags - the command/function found in the snippet, e.g. update_term_meta.
- Tags - general taxonomy based on various criteria, e.g. WordPress (based on CMS), Laravel (based on the framework) etc. 
- Language - IETF language tag of the source, e.g. en-US.
- Codelang tags - Tag for the code language, e.g. PHP, JavaScript etc.

### Frontend
Frontend is built on [React.js](https://react.dev/) with [Tailwind CSS](https://tailwindcss.com/).

## License 

docs_dangit is a free software, and complete code inside it is released under the terms of the GNU General Public License version 3 or later. This does not apply to Google fonts and other 3rd party assets - their original license applies. 

------------------------------------------------

**by Cloudfest Hackathon Cool Kids**
