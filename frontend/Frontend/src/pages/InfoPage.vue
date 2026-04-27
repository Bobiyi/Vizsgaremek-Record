<script setup>
import { useStore } from "../stores/store";
import { onMounted } from "vue";
const store = useStore()
onMounted(() => {
  store.getInfo();
});

</script>
<template>

  <q-page class="flex flex-center" v-if="store.response != null">
   
    <div>

        <h2>{{ store.response.appName }} v{{ store.response.version }}</h2>
        <h3>Készítette: {{ store.response.authors["backend"] }}, {{ store.response.authors["frontend"] }}</h3>
        <h3><a :href="store.response.gitRepository">GitHub</a></h3>

    </div>

  </q-page>

</template>
<style lang="scss" scoped>
div{
    color:white;
    text-align: center;
}
a{
    color: $deep-purple-13;
}
a:active{
    color: $pink-13;
}
</style>

