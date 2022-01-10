FROM thecodingmachine/nodejs:14

# App defaults (Change me)
ENV APP_NAME "Symfony Boilerplate"
ENV DEFAULT_LOCALE "en"
ENV HOST "0.0.0.0"
ENV NUXT_PORT "3000"
EXPOSE 3000

# Copy files.
# Don't forget to create a .env file with required Nuxt.js variables.
COPY --chown=docker:docker . .

# Install dependencies.
USER docker
RUN yarn install --silent

# Build the application.
RUN yarn build

# Define default command.
CMD ["yarn", "start"]
