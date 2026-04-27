<script setup>
import { Cookies, useQuasar } from 'quasar';
import { onMounted, ref  } from "vue";
import { useRouter } from 'vue-router'
import { useStore } from "../stores/store";

const Router = useRouter()
const store = useStore();

const $q = useQuasar()

if(!Cookies.has('user') || !(Cookies.get('user').user.role == 'admin')){
    $q.notify({
        message: 'Nincs engedélyed erre az oldalra',
        color: 'red',
        position: 'top',
        timeout: 3000,
        icon: 'cancel'
        })
  Router.push("/login")
}



const requestColumns = ref([
  { name: 'id', align: 'left', label: 'ID', field: 'id', sortable: true },
  { name: 'userName', label: 'Felhasználó', field: 'userName', sortable: true },
  { name: 'type', label: 'tipus', field: 'type',  sortable: true},
  { name: 'data', label: 'data', field: 'data'  },
  { name: 'checked', label: 'létrehozva', field: 'createdAt', sortable: true },
  { name: 'router', label: '', field: '' },
])

const recordColumns = ref([
  { name: 'id', align: 'left', label: 'ID', field: 'id', sortable: true },
  { name: 'name', label: 'Nev', field: 'name', sortable: true },
  { name: 'typeName', label: 'tipus', field: 'typeName',  sortable: true},
  { name: 'releaseYear', label: 'lemez', field: 'releaseYear'  },
  { name: 'lenght', label: 'hossz', field: 'length', sortable: true },
  { name: 'router', label: '', field: '' },
])
const artistColumns = ref([
  { name: 'id', align: 'left', label: 'ID', field: 'id', sortable: true },
  { name: 'artistName', label: 'nev', field: 'artistName', sortable: true },
  { name: 'activeSince', label: 'aktiv', field: 'activeSince',  sortable: true},
  { name: 'nationality', label: 'nemzetiseg', field: 'nationality'  },
  { name: 'router', label: '', field: '' },
])
function rowClassFn (row) {
        return row.type == "new_record" ? 'bg-deep-purple-6' : 'bg-deep-purple-4'
}

onMounted(async () => {
  await store.getRequests(Cookies.get("user").token);
  await store.getArtists();
  await store.getRecords();
});
  

</script>

<template>
  <q-page class="flex flex-center">


<div >
  <q-table
        color="white"
        card-class="bg-deep-purple-13 text-white"
        :table-row-class-fn="rowClassFn"
        flat
        bordered
        title="Requests"
        :rows="store.requests"
        :columns="requestColumns"
        class="table"
  >
    <template v-slot:body-cell-data="props">
                  <q-td :props="props">
                    <p v-if="props.row.type == 'new_record'">{{ props.row.data.recordName }}</p>
                    <p v-if="props.row.type == 'new_artist'">{{ props.row.data.artistName }}</p>
                  </q-td>
    </template>
    <template v-slot:body-cell-router="props">
                  <q-td :props="props">
                    <q-btn flat round icon="mdi-arrow-right" :to="`../adminPanel/${props.row.id}`" />
                  </q-td>
    </template>
  
  </q-table>
  
  
  
  
  <q-table
        color="white"
        card-class="bg-deep-purple-13 text-white"
        body-tr-class="bg-red"
        flat
        bordered
        title="Records"
        :rows="store.records"
        :columns="recordColumns"
        class="table records"
  >

    <template v-slot:body-cell-router="props">
                  <q-td :props="props">
                    <q-btn flat round icon="mdi-arrow-right" :to="`../records/${props.row.id}`" />
                  </q-td>
    </template>
  
  </q-table>
  
  
  
  <q-table
        color="white"
        card-class="bg-deep-purple-13 text-white"
        flat
        bordered
        title="Artists"
        :rows="store.artists"
        :columns="artistColumns"
        class="table artists"
  >
    <template v-slot:body-cell-router="props">
                  <q-td :props="props">
                    <q-btn flat round icon="mdi-arrow-right" :to="`../artists/${props.row.id}`" />
                  </q-td>
    </template>
  
  </q-table>
</div>



  </q-page>
</template>




<style lang="scss" scoped>
.table{
  width: 60em;
  margin-bottom: 5em;
  margin-top: 5em;
}

.artists :deep(tbody tr){
  background-color: $deep-purple-4;
}
.records :deep(tbody tr){
  background-color: $deep-purple-6;
}
</style>