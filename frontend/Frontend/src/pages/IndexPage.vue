<script setup>

function scrollTo(){
document.getElementById('content').scrollIntoView({ behavior: 'smooth' })
}
</script>
<template>
  <q-page class="flex flex-center">
    <q-parallax
    style="height: 90vh;"
    class="blur"
    src="~/assets/images/bg1.jpg"
    fit="cover"
  > 
  <div class="absolute-top-center q-pa-md title text-white q-px-sm q-py-sm flex">
    <h3 style="vertical-align: middle;">
            <q-icon> <img src="~/src/assets/Record_Logo_Outline.svg" alt="">
            </q-icon> Record
          </h3>
  </div>
      <q-btn @click="scrollTo()" flat class="scroll-arrow absolute-bottom">
      <q-icon  name="keyboard_arrow_down" size="48px" color="white" />
      </q-btn>
  </q-parallax>

    <div id="content">
      <h3>Üdvözlünk a Record-on</h3>
      <h5>A Record egy egyszerű, ám szenvedélyes hely mindazok számára, akik szeretik a zenét. Albumokat, EP-ket, kislemezeket és előadókat gyűjtünk össze egy helyen, hogy mindig kéznél legyen minden, amit hallgatsz, vagy amit még fel kell fedezned.</h5>
      <h5>Jelölj kedvencnek albumokat, böngéssz előadók szerint, és ha valami hiányzik, bátran kérheted új zenészek vagy kiadványok felvételét, a gyűjtemény folyamatosan bővül.</h5>
      <div class="options">
          <div class="option col-12">
            <h5>Albumok & EP-k</h5>
            <h6>böngészés és felfedezés</h6>
          </div>
          <div class="option col-12 ">
            <h5>Kedvencek</h5>
            <h6>mentsd el, amit szeretsz</h6>
          </div>
          <div class="option col-12">
            <h5>Kérj újat</h5>
            <h6>ha valami hiányzik</h6>
          </div>
      </div>
    </div>




  </q-page>
</template>
<style lang="scss" scoped>

.options{
  width: 100%;
  display: flex;
  justify-content: space-around;
  flex-wrap: wrap;
}
.option{
color: white;
background-color: rgba(255, 255, 255, 0.11);
padding: 2em;
border-radius: 15px;
height: 9.3em;
margin-top: 2em;
}
.option h5, .option h6{
  margin: 5px;
}

.title h3{
font-size: 600%;

}
#content{
  margin-top: 10em;
  width: 80vw;

}
h3{
  margin: 0px;
  color: white;
}
h5{
  color: white;
}

.blur :deep(.q-parallax__media) {
  filter: blur(2px);
  filter: brightness(0.6);
}
</style>

