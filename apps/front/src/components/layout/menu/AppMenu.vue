<script setup>
import { ref, computed } from "vue";
import { API_URL } from "~/constants/http";
import { useAuthUser } from "~/store/auth";
import { useRouter } from 'vue-router';


const authStore = useAuthUser();
const username = authStore?.me?.first_name+' '+ authStore?.me?.last_name || "";

const userLinks = [
{
    label: username,
    active:false,
    avatar: {
    src: 'https://avatars.githubusercontent.com/u/739984?v=4'
  },
  },
];

const adminLinks = [
  {
    label: "Utilisateurs",
    icon: "i-heroicons-users",
    to: "/users",
  },
];

const baseLinks = [

  {
    label: "Tableau de bord",
    icon: "i-heroicons-home",
    to: "/",
  },
  {
    label: "Paiements",
    icon: "i-heroicons-chart-bar",
    to: "/payments",
  },
  {
    label: "Déconnexion",
    icon: "i-heroicons-arrow-left",
    click: () => logout(),
  },
];



const links = computed(() => {
  return authStore.isAdmin ? [...userLinks,...adminLinks, ...baseLinks] : [...userLinks,...baseLinks];
});


const router = useRouter();

async function logout() {

  try {
    await fetch(API_URL+'/logout', {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
    });
    console.log('done');
  } catch (e) {
    await setError(e);
    return; // If there's an error, you may not want to redirect. Remove this if that's not the case.
  }

  // Redirect to the login page
  router.push('/auth/login').catch(err => {
    // Handle routing errors
    console.error('Routing error:', err);
  });
}
</script>

<template>
  <UVerticalNavigation :links="links" />
</template>
