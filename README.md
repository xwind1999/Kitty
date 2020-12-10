Clone the repository
Change the directory to the current project
RUN: docker-compose up -d
Access to kitty_php container : docker exec -it kitty_php sh
RUN composer install
Go to web brower and access localhost
