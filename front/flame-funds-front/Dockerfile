# base image
FROM node:16.3.0-alpine

RUN apk add --no-cache tzdata
ENV TZ Europe/Warsaw

# set working directory
WORKDIR /app

RUN chown -R node.node /app

# add `/app/node_modules/.bin` to $PATH
ENV PATH /app/node_modules/.bin:$PATH

# install and cache app dependencies
COPY package.json /app/package.json
RUN npm install
RUN npm install vue@^3.2.26
RUN npm install @vue/cli@3.7.0 -g

# start app
CMD ["npm", "run", "serve"]
