FROM mysql:8
MAINTAINER J.GAUTHI <github.com/jgauthi>

ARG DB_USER
ARG DB_PASSWORD
ARG DB_PORT

# Securise line command "mysql" and "mysqldump" (you don't need to specify user + password)
RUN printf "[client]\n\
host=localhost\n\
user=$DB_USER\n\
password=$DB_PASSWORD\n\
port=$DB_PORT\n\
default-character-set=utf8" >> ~/.my.cnf
