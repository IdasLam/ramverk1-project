[![Build Status](https://travis-ci.com/IdasLam/ramverk1-project.svg?branch=main)](https://travis-ci.com/IdasLam/ramverk1-project)
[![Build Status](https://scrutinizer-ci.com/g/IdasLam/ramverk1-project/badges/build.png?b=main)](https://scrutinizer-ci.com/g/IdasLam/ramverk1-project/build-status/main)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/IdasLam/ramverk1-project/badges/quality-score.png?b=main)](https://scrutinizer-ci.com/g/IdasLam/ramverk1-project/?branch=main)
# Ramverk1 Project

This project was developed for the course DV1610 at Blekinge Institute of Technology during H20 term. The web application is supposed to be a simpler forum, where you could post and comment. Read more about the features on [dbwebb](https://dbwebb.se/kurser/ramverk1-v2/kmom10).

## Prerequisites

### System
Running this project will require you to have `docker`, `docker-compose` on your system.

### Other
Clone the [reposiory](https://github.com/IdasLam/ramverk1-project) from GitHub.

## Setup
Before starting make sure you have everything setup under Prerequisites.

Follow this setup guide to make sure that the application can run correctly. Please make sure to clone the project from GitHub and also make sure that your terminal is in the folder right before you start.

1. Run `make install` in your terminal.
4. Run `docker-compose up` in your terminal.
5. Go to [http://localhost/](http://localhost/).

Now the web application should be setup and running!

### Notes

#### Tests
Make sure that you have `sqlite3` on your system before testing.
Before running the test please run `sqlite3 data/db.sqlite < sql/ddl.sql` then run `make test` to create the test. The results will be available at `build/coverage`.
