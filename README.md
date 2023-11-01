
# Athens Exchange Group Website

## Docker Dev Commands

1. Build base image
	> docker build -f athex-base.Dockerfile -t athex-base .
2. Build dev image
	> docker build -f athex-dev.Dockerfile -t athex-dev .
3. Run a container with the dev image and with a binding to the current path
	> docker run -it --rm -p 8088:80 -v $(pwd):/var/www/html athex-dev
4. Install/update composer dependencies within the container
	> composer require
