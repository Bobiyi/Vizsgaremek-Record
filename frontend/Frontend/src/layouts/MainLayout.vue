<template>
  <q-layout view="hHh lpR fff">
    <q-header  class="header">
      <q-toolbar>
        <q-btn flat dense round icon="menu" aria-label="Menu" @click="toggleLeftDrawer" />

        <q-toolbar-title>

          <router-link to="/" class="header-router">
            <q-avatar>
              <img src="~/src/assets/Record_Logo_Outline.svg" alt="">
            </q-avatar>
            Record
          </router-link>

          <div style="float: right;"><q-btn align="left" 
            class="full-width q-ma-xs " 
            :class="{ active: $route.path === '/login' }" 
            clickable flat no-caps
            icon="mdi-account"   
            :label="Cookies.has('user') ?  Cookies.get('user').user.userName : 'Bejelentkezés'" 
            :to="Cookies.has('user') ? '/profile' :'/login'"/></div>
        </q-toolbar-title>

        
      </q-toolbar>
    </q-header>

     <q-drawer overlay v-model="leftDrawerOpen" side="left" class="drawer" :width="150">
        <!-- drawer content -->
        <q-scroll-area class="fit">
          <q-btn align="left" class="full-width q-ma-xs drwropt" :class="{ active: $route.path === '/' }" clickable flat
            icon="mdi-home" label="Fő oldal" no-caps to="/"/>
            <q-btn align="left" class="full-width q-ma-xs drwropt" :class="{ active: $route.path === '/artists' }" clickable flat
            icon="mdi-account-music" label="Zenészek" no-caps to="/artists" />
            <q-btn align="left" class="full-width q-ma-xs drwropt" :class="{ active: $route.path === '/records' }" clickable flat
            icon="mdi-album" label="Lemezek" no-caps to="/records" />
            <q-btn align="left" class="full-width q-ma-xs drwropt" :class="{ active: $route.path === '/newRequest' }" clickable flat
            icon="mdi-plus-circle-outline" label="Új kérés" no-caps to="/newRequest" />
        </q-scroll-area>    
      </q-drawer>

    <q-page-container class="content" style="padding-bottom: 25vh;">
      <router-view />
    </q-page-container>

    <q-footer bordered class="bg-deep-purple-14 text-white" >
      <q-toolbar  style="text-align: center;">
        <q-toolbar-title>
          <!--logo-->
          <div>
            <q-avatar>
              <img src="~/src/assets/Record_Logo.svg" alt="">
            </q-avatar> 
            Record
          </div>

          <!--linkek-->
          <div>
            <q-btn flat round icon="mdi-github" href="https://github.com/Bobiyi/Record_Vizsga" target="_blank"/>
            <q-btn flat round icon="mdi-information" to="/about"/>

          </div>
          <!--készítők-->
          <div>Halmai Bence, Kiss Erik Adolf</div>
        </q-toolbar-title>
        
      </q-toolbar>
    </q-footer>
  </q-layout>
</template>

<script setup>
import { Cookies } from 'quasar';
import { ref } from 'vue'


const leftDrawerOpen = ref(false)

function toggleLeftDrawer() {
  leftDrawerOpen.value = !leftDrawerOpen.value
}

</script>

<style lang="scss">
.header-router{
  text-decoration: none; 
  color: $deep-purple-13;
}
.header{
  background-color: $darker;
  color: $deep-purple-13;
}
.content{
    background-color: $dark ;
 
}
.drawer{
  background-color: $darker;
  color: $deep-purple-13;
  border-right: 0.1px dotted $deep-purple-13 ;
  width: 200px;
}
.drwropt{
  border-left: 4px $deep-purple-13 solid;
}
*{
  @font-face {
    font-family: 'Outfit';
    src: url('../css/fonts/Outfit-VariableFont_wght.ttf');
  }


 font-family: "Outfit",'roboto-font'
}
.album-img{
  width: 21em;
  height: 21em;
  border-radius: 15px !important;
  overflow: hidden;
}
</style>