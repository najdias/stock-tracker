FROM node:lts-alpine

WORKDIR /app

COPY package*.json ./

RUN yarn install

# copy project files and folders to the current working directory (i.e. 'app' folder)
COPY . .

EXPOSE 8080
CMD [ "yarn", "dev", "--host", "0.0.0.0" ]
