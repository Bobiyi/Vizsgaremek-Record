<script setup>
import { useQuasar } from 'quasar'
import { ref } from 'vue'
import { useStore } from "../stores/store";
import { Cookies } from 'quasar';
import { useRouter } from 'vue-router'
    const store = useStore();
    const Router = useRouter();
    const $q = useQuasar()

    if(Cookies.has('user')){
      Router.push("/profile")
    }
    const isPwd= ref(true)

    const name = ref("")
    const password = ref("")

    async function onSubmit () {
      var loginInfo = {
        'userName': name.value,
        'password': password.value
    };

      await store.login(loginInfo)

      if(store.userData?.user != null){ //success
        $q.notify({
        message: 'Sikeres bejelentkezés!',
        color: 'green',
        position: 'top',
        timeout: 3000,
        icon: 'check_circle'
        })
        Cookies.set('user', store.userData, {expires: 3})
        Router.push("/profile")
      }else{ //fail
        $q.notify({
        message: 'Hibás felhasználónév vagy jelszó!',
        color: 'red',
        position: 'top',
        timeout: 3000,
        icon: 'cancel'
        })
      }

    }

</script>

<template >
  <q-page class="flex flex-center">

    <q-form
      @submit="onSubmit"
      class="q-gutter-md form"
    >
        <div class="text-h6">Bejelentkezés</div>

      <q-input
        filled
        v-model="name"
        label="Felhasználó név"
        class="input"
        color="deep-purple-13"
      />

      <q-input
        filled
        v-model="password"
        label="Jelszó"
        :type="isPwd ? 'password' : 'text'"
        class="input"
        color="deep-purple-13">
        <template v-slot:append>
          <q-icon
            :name="isPwd ? 'visibility_off' : 'visibility'"
            class="cursor-pointer"
            @click="isPwd = !isPwd"
          />
        </template>
        </q-input>

      <div>
        <q-btn label="Belépés" type="submit" color="deep-purple-13"/>
        <q-btn label="Regisztráció"  color="deep-purple-13" to="/register" flat class="q-ml-sm"/> 
      </div>
    </q-form>

  </q-page>
</template>

<style lang="scss" scoped>

.form{
  width: 20em;
}
.input{
  width: 100%;
  background-color:  white;
} 
.text-h6{
  color: $deep-purple-13;
}

</style>



