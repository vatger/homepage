FROM nginx:latest

LABEL maintainer='VATSIM Germany <support@vatsim-germany.org>'

ENV URL='dev.vatsim-germany.org'
ENV DOCKER_DIR='./docker/8.1_build'
ENV PHP_FPM='TRUE'

# Install required packages
COPY ${DOCKER_DIR}/init.sh /home
COPY ${DOCKER_DIR}/install_req.sh /home
RUN ["chmod", "+x", "/home/install_req.sh"]
RUN ["chmod", "+x", "/home/init.sh"]
RUN /home/install_req.sh

# Create required directories
RUN mkdir -p /var/www/${URL}
RUN mkdir -p /etc/nginx/sites_enabled/

# Remove unused config files for NGINX
WORKDIR /etc/nginx/conf.d
RUN rm default.conf

# Copy new files for NGINX
WORKDIR /etc/nginx
RUN rm nginx.conf
COPY ${DOCKER_DIR}/nginx_conf/nginx.conf /etc/nginx/
COPY ${DOCKER_DIR}/nginx_conf/dev.vatsim-germany.org /etc/nginx/sites_enabled/

# Copy the git files
COPY . /var/www/${URL}

CMD [ "/home/init.sh" ]