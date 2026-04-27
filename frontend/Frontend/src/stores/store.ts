import { defineStore } from "pinia";
import { api } from "src/boot/axios";
import { Notify } from "quasar";

function notifyFail(text){
  Notify.create({
    message: text,
    color: 'red',
    position: 'top',
    timeout: 3000,
    icon: 'error'
  })
}

function notifySuccess(text){
  Notify.create({
    message: text,
    color: 'green',
    position: 'top',
    timeout: 3000,
    icon: 'check'
  })
}
//interfaces--------------------------------------
export interface IUser {
  token?: string;
  id?: number;
  userName?: string;
  email?: string;
  role?: string;
  phoneNumber?: number;
}
export interface IRecord {
  id?: number;
  artistName?: string[];
  name?: string;
  typeName?: string;
  releaseYear?: number;
  length?: number;
  coverUrl?: string;
}

export interface IArtist {
  id?: number;
  artistName?: string;
  activeSince?: string;
  nationality?: string;
  website?: string;
  isGroup?: boolean;
  artistIconPath?: string;
  artistCoverPath?: string;
}

export interface IRequest {
  id?: number;
  userId?: number;
  userName?: string;
  type?: string;
  data?: IRequestData;
  createdAt?: string;
}

export interface INewArtistData {
  artistName: string;
  activeSince: string | null;
  artistNationality: string | null;
  artistWebsite: string | null;
  isGroup: number;
  iconUrl: string | null;
  coverUrl: string | null;
}



export interface INewAlbumData {
  recordName: string;
  type: string;
  releaseYear: string | null;
  length: number | null;
  coverUrl: string | null;
}

export type IRequestData = INewArtistData | INewAlbumData;



interface IState {
  userData: IUser | null;
  registeredUser: IUser;
  favourites: number[];
  records: IRecord[];
  record: IRecord;
  artists: IArtist[];
  artist: IArtist;
  requests: IRequest[]
  request: IRequest;
  response: string;
  loading: boolean;
  error: string | null;
}


//store --------------------------------------------------------
export const useStore = defineStore("store", {
  state: (): IState => ({
    userData: null,
    registeredUser: null,
    favourites: [],
    records: [],
    record: null,
    artists: [],
    artist: null,
    requests: [],
    response: null,
    request: null,
    loading: false,
    error: null,
  }),
  getters: {
    recordsSortedByName: (state) => {
      return [...state.records].sort((o1, o2) =>
        (o1.name ?? '').localeCompare(o2.name ?? '')
      )
    },
    recordsSortedByArtistName: (state) => {
      return [...state.records].sort((o1, o2) =>
        (o1.artistName?.[0] ?? '').localeCompare(o2.artistName?.[0] ?? '')
      )
    },
    recordsSortedByRelease: (state) => {
      return [...state.records].sort((o1, o2) =>
        (o2.releaseYear ?? 0) - (o1.releaseYear ?? 0)  // use numeric sort for years
      )
    },
    recordsSortedByType: (state) =>{
      return [...state.records].sort((o1,o2) => o1.typeName.localeCompare(o2.typeName))
    },
    ArtistsSortedByName: (state) =>{
      return [...state.artists].sort((o1,o2) => o1.artistName.localeCompare(o2.artistName))
    }
  },

  actions: {
// --- Összes record lekérése ---
    async getRecords() {
      this.loading = true;
      try {
        const res = await api.get("/records");
        this.records = res.data;
        this.error = null;
      } catch (err) {
        notifyFail("Hiba a kiadások betöltésekor")
      } finally {
        this.loading = false;
      }
    },

    // --- Egy record lekérése saját id-je alapján ---
    async getRecordById(id: number): Promise<void> {
      this.loading = true;
      try {
        const res = await api.get(`/records/${id}`);
        this.record = res.data;
        this.error = null;
      } catch (err) {
        notifyFail('Hiba a kiadás betöltésekor!')
      } finally {
        this.loading = false;
      }
    },

    // --- record lekérése artist id alapján ---
    async getRecordByArtistId(id: number): Promise<void> {
      this.loading = true;
      try {
        const res = await api.get(`/artists-record/${id}`);
        this.records = res.data;
        this.error = null;
      } catch (err) {
        notifyFail('Hiba a kiadás betöltésekor!')
      } finally {
        this.loading = false;
      }
    },


    // --- record torlese id alapján ---
    async deleteRecord(id: number, token: string): Promise<void> {
      this.loading = true;
      try {
        await api.delete(`/records/${id}`,{
          headers:{
            "Content-Type": 'application/json',
            "Authorization": `Bearer ${token}`
          },
        });
        notifySuccess('Kiadás sikeresen torolve!')
        this.error = null;
      } catch (err) {
        notifyFail('Hiba a kiadás torlesekor!')
      } finally {
        this.loading = false;
      }
    },

  //update record  
  async updateRecord(formData: FormData, token: string, recordId: number) {
      try {
        await api.post(`/records/${recordId}`, formData,{
          headers:{
            'Content-Type': 'multipart/form-data',
            "Authorization": `Bearer ${token}`,
          }
        });
          notifySuccess('Sikeresen frissítve lettek az adatok')
          this.error = null;
      } catch (err) {
        notifyFail('A frissités sikertelen volt!')
      }
    },


    // --- Összes artist lekérése ---
    async getArtists() {
      this.loading = true;
      try {
        const res = await api.get("/artists");
        this.artists = res.data;
        this.error = null;
      } catch (err) {
        notifyFail('Hiba a kiadók betöltésekor!')
      } finally {
        this.loading = false;
      }
    },

    // --- Egy artist lekérése saját id alapján ---
    async getArtistById(id: number): Promise<void> {
      this.loading = true;
      try {
        const res = await api.get(`/artists/${id}`);
        this.artist = res.data;
        this.error = null;
      } catch (err) {     
        notifyFail('Hiba a kiadó betöltésekor!')
      } finally {
        this.loading = false;
      }
    },

    // --- artistok lekérése record id alapján ---
    async getArtistsByRecordId(id: number) {
      this.loading = true;
      try {
        const res = await api.get(`/records-artist/${id}`);
        this.artists = res.data;
        this.error = null;
      } catch (err) {
        notifyFail('Hiba a kiadó betöltésekor!')
      } finally {
        this.loading = false;
      }
    },

// --- artist torlese id alapján ---
    async deleteArtist(id: number, token: string): Promise<void> {
      this.loading = true;
      try {
        await api.delete(`/artists/${id}`,{
          headers:{
            "Content-Type": 'application/json',
            "Authorization": `Bearer ${token}`
          },
        });
        notifySuccess('Kiadó sikeresen torolve!')
        this.error = null;
      } catch (err) {
        notifyFail('Hiba a kiadó törlésekor!')
      } finally {
        this.loading = false;
      }
    },
    async updateArtist(formData: FormData, token: string, recordId: number) {
      try {
        await api.post(`/artists/${recordId}`, formData,{
          headers:{
            'Content-Type': 'multipart/form-data',
            "Authorization": `Bearer ${token}`,
          }
        });
          notifySuccess('Sikeresen frissítve lettek az adatok')
          this.error = null;
      } catch (err) {
        notifyFail('A frissités sikertelen volt!')
      }
    },

    // felhasználó kezelés
    async login(loginData: {'userName': string, 'password': string}): Promise<void> {
      this.loading = true;
      try {
        const res = await api.post(`/user/login`,JSON.stringify(loginData),{
          headers:{
            "Content-Type": 'application/json',
          },
        });
        this.userData = res.data;
        this.error = null;
      } catch (err) {
      } finally {
        this.loading = false;
      }
    },

      async logout(token: string): Promise<void> {
      this.loading = true;
      try {

        const res = await api.post(`/user/logout`,{},{
          headers:{
            "Content-Type": 'application/json',
            "Authorization": `Bearer ${token}`,
          },
        });
        this.userData = null;
        notifySuccess('Sikeres kijelentkezés!')
        this.error = null;
      } catch (err) {
        notifyFail( 'Hiba a kijelentkezés során!')
      } finally {
        this.loading = false;
      }
    },

      async register(registerData: {
        'password': string,
         'userName': string,
          'email': string,
           'phoneNumber': string}): Promise<void> {
      this.loading = true;
      try {

        const res = await api.post(`/user/register`,JSON.stringify(registerData),{
          headers:{
            "Content-Type": 'application/json',
          },
        });
        this.registeredUser = res.data;
        notifySuccess('Sikeres regisztráció!')
        this.error = null;
      } catch (err) {     
        if(err?.response?.data?.message == "The user name has already been taken."){
          notifyFail('A felhasználónév már foglalt!')
        }else{
          notifyFail('Hiba a regisztráció során!')
        }
      } finally {
        this.loading = false;
      }
    },

    //get favs
    async getFavourites(id: number): Promise<void> {
      this.loading = true;
      try {
        const res = await api.get(`/favourite/${id}`);
        this.favourites = res.data;
        this.error = null;
      } catch (err) {
        notifyFail('Hiba a kedvencek betöltése során!')
      } finally {
        this.loading = false;
      }
    },
    async getFavouritesRecord(id:number): Promise<void> {
      this.loading = true;
      try {
        const res = await api.get(`/favourite/${id}/list`);
        this.records = res.data;
        this.error = null;
      } catch (err) {
        notifyFail('Hiba a kedvencek betöltése során!')
      } finally {
        this.loading = false;
      }
    },
    // toggle favs
    async toggleFavourite(id: number, token: string){
      this.loading = true;
      try {
        await api.post(`/favourite`,JSON.stringify({"recordId": id}),{
          headers:{
            "Content-Type": 'application/json',
            "Authorization": `Bearer ${token}`
          },
        });
        this.error = null;
      } catch (err) {
        notifyFail('Kedvencelés sikertelen volt!')
      } finally {
        this.loading = false;
      }
    },

    //  új request létrehozása
    async createRequest(formData: FormData, token: string) {
      try {
        const res = await api.post("/queue-request", formData,{
          headers:{
            'Content-Type': 'multipart/form-data',
            "Authorization": `Bearer ${token}`,
          }
        });
        notifySuccess('Sikeresen létrelett hozva a kérelem!')
        this.response = res.data
        this.error = null;
      } catch (err) {
        notifyFail('A kérelem létrehozása sikertelen volt!')
      }
    },

    //összes request lekérése 
    async getRequests(token: string) {
      try {
        const res = await api.get("/requests", {
          headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${token}`,
          }
        });
        this.requests = res.data;
        this.error = null;

      } catch (err) {
        notifyFail('Hiba a kérelmek betöltése során!')
      }
    },

    // request id alapjan
    async getRequest(id: number, token: string){
      this.loading = true;
      try {
        const res = await api.get(`/requests/${id}`,{
          headers:{
            "Content-Type": 'application/json',
            "Authorization": `Bearer ${token}`
          },
        });
        this.request = res.data
        this.error = null;
      } catch (err) {
        notifyFail('Hiba a kérelem betöltése során!')
      } finally {
        this.loading = false;
      }
    },
    async acceptRequest(id: number, payload: {'adminNote': string}, token: string){
      this.loading = true;
      try {
        const res = await api.patch(`/request/${id}/accept`,JSON.stringify(payload),{
          headers:{
            "Content-Type": 'application/json',
            "Authorization": `Bearer ${token}`
          },
        });
        notifySuccess('Sikeresen el lett fogadva!')
        this.response = res.data
        this.error = null;

      } catch (err) {
        notifyFail('Hiba az elfogadas soran!')
      } finally {
        this.loading = false;
      }
    },

    async rejectRequest(id: number, payload: {'adminNote': string}, token: string){
      this.loading = true;
      try {
        const res = await api.patch(`/request/${id}/reject`,JSON.stringify(payload),{
          headers:{
            "Content-Type": 'application/json',
            "Authorization": `Bearer ${token}`
          },
        });
        notifySuccess('Sikeresen el lett utasitva!')
        this.response = res.data
        this.error = null;

      } catch (err) {
        notifyFail('Hiba az elutasitás során!')
      } finally {
        this.loading = false;
      }
    },
    async joinRecordArtist(payload: {'artistId':number, 'recordId':number}, token: string){
      this.loading = true;
      try {
        const res = await api.post(`/link-artist-record`,JSON.stringify(payload),{
          headers:{
            "Content-Type": 'application/json',
            "Authorization": `Bearer ${token}`
          },
        });
        this.response = res.data
        notifySuccess("Sikeres összekötés!")
        this.error = null;
      } catch (err) {
        notifyFail("Az összekötés sikertelen!")
      } finally {
        this.loading = false;
      }
    },
    async getInfo() {
      this.loading = true;
      try {
        const res = await api.get("/about");
        this.response = res.data;
        this.error = null;
      } catch (err) {
        notifyFail("Hiba a kiadások betöltésekor")
      } finally {
        this.loading = false;
      }
    },

  },
});