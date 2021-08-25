FROM thecodingmachine/nodejs:14

# Copy files.
# Don't forget to create a .env file with required Nuxt.js variables.
COPY --chown=docker:docker . .

# Install dependencies.
RUN yarn

# Build the application.
RUN yarn build

# Define default command.
CMD ["yarn", "start"]
