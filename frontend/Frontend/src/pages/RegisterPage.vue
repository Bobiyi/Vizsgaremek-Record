<script setup>
import { ref } from 'vue'
import { useStore } from "../stores/store";
import { Cookies } from 'quasar';
import { useRouter } from 'vue-router'
    const userStore = useStore();
    const Router = useRouter();

    const name = ref("")
    const password = ref("")
    const email = ref("")
    const phoneNumber = ref(null)

    async function onSubmit () {

      //adatok
      var registerData = {
        'userName': name.value.trim(),
        'password': password.value.trim()
    };
    if(email.value.trim() != ""){
      registerData.email = email.value.trim()
    }
    if(phoneNumber.value != null){
      registerData.phoneNumber = phoneNumber.value
    }
    //register próba
      await userStore.register(registerData);
    if(userStore.registeredUser?.user != null){ //HA register sikeres akkor loginel
      var loginData = {
        'userName': registerData.userName,
        'password': registerData.password
      };
      await userStore.login(loginData)
      userStore.registeredUser = null
      if(userStore.userData.user != null){ //success
        Cookies.set('user', userStore.userData, {expires: 3})
        Router.push("/profile")
      }
    }
      

    }
</script>


<template>
<q-page class="flex flex-center">

    <q-form
      @submit="onSubmit"
      class="q-gutter-md form"
    >
    <div class="text-h6">Regisztráció</div>

      <q-input
        filled
        v-model="name"
        label="Felhasználó név"
        class="input"
        color="deep-purple-13">
        <template #label>
            Felhasználó név <span class="text-red">*</span>
        </template>
      </q-input>

      <q-input
        filled
        type="password"
        v-model="password"
        label="Jelszó"
        class="input"
        color="deep-purple-13">
        <template #label>
            Jelszó <span class="text-red">*</span>
        </template>
      </q-input>
      
      <q-input
        filled
        v-model="email"
        label="Email"
        class="input"
        color="deep-purple-13"
        type="email"
      />
      <q-input
        filled
        v-model="phoneNumber"
        label="Telefonszám"
        class="input"
        color="deep-purple-13"
        type="tel"
      />
      <div>
        <q-btn label="Regiszració" type="submit" color="deep-purple-13"/>
        <q-btn label="Bejelentkezés"  color="deep-purple-13" to="/login" flat class="q-ml-sm"/> 
      </div>
    </q-form>

  </q-page>
</template>

<style lang="scss" scoped>

h1{
    color: $deep-purple-13;
}
.form{
  width: 25em;
}
.input{
  width: 100%;
  background-color:  white;
} 
.text-h6{
    color: $deep-purple-13;
}

</style>

