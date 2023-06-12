# Deploy with kubernetes

To deploy this project onto kubenetes, follow theses instructions. 

## include `gitlab-ci/deploy-k8s.gitlab-ci.yml`

In the root file `/gitlab-ci.yml` unsure you have this part :  
```yaml 
include:
  - local: gitlab-ci/deploy-k8s.gitlab-ci.yml
```

## Assign secrets variables to your gilab-ci

1. Assign `KUBE_CONFIG` with the kube config file of your remote cluster (assign with good rights managements)
2. Assign `KUBERNETES_PASSWORD_SECRET` with a strong password (it's will be used for all secrets related to your deployment : APP_SECRET, MYSQL_PASSWORD, htpasswd, etc)

Tips: Protects theses secrets to be available only for protected branches (if it relevant in your workflow)

## Assign publics variables to your local gilab-ci

Edit `gitlab-ci/deploy-k8s.gitlab-ci.yml` and adjust the variables :
1. Assign `KUBERNETES_URL_SUFFIX` to the suffix of the url
2. 

## Adjust rules

Edit `gitlab-ci/deploy-k8s.gitlab-ci.yml` and adjust the rules : 
```yaml

```