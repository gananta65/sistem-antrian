# nginx/Dockerfile.nginx
FROM nginx:alpine

WORKDIR /etc/nginx/conf.d

COPY default.conf .  

EXPOSE 80

CMD ["nginx", "-g", "daemon off;"]
