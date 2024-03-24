---
sidebar_position: 1
---
# Create your project

## Initialize your project with the boilerplate's code base

1. From and existing git repo (should be empty at that time), register boilerplate's remote
```bash title="terminal"
git remote add boilerplate https://github.com/thecodingmachine/symfony-boilerplate.git
```

2. Pull the source code from a release to your current branch
```bash title="terminal"
git pull boilerplate v2
```
:::tip
Keep this remote if you want to stay up to date with new versions !
:::

## Customize with your project's specifics
Now it's time to adapt the boilerplate to your project...

**1. Set your app's name**

At least you'll want to update the `APP_NAME` variable in the `.env.dist` file to match your project's name (EG. my-awsome-app).

**2. Update README.md**
    
You might want to update the README file, to describe your app's purpose, and define the coding standards you want to define.

## Push the initial codebase

Now you can push the boilerplate's code and start implementing !

```bash title="terminal"
git commit -am "Initial commit of the boilerplate's code base"
git push origin main
```
