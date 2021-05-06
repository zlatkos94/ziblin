<template>
  <CRow>
    <CCol col="12" lg="6">
      <CCard>
        <CCardBody>
          <h3>
            Edit Roadmap id:  {{ $route.params.id }}
          </h3>
          <CAlert
              :show.sync="dismissCountDown"
              color="primary"
              fade
          >
            ({{dismissCountDown}}) {{ message }}
          </CAlert>
          <CSelect
              :value.sync="roadmap.fk_user"
              :options="users"
              label="Fk users"
          />
          <CSelect
              :value.sync="roadmap.fk_client"
              :options="clients"
              label="Fk clients"
          />

<!--          <CInput-->
<!--              label="Client"-->
<!--              placeholder="Input client.."-->

<!--              textarea="true"-->
<!--              rows="9"-->
<!--              v-model="roadmap.description"-->
<!--          />-->

<!--          <CSelect-->
<!--              :value.sync="roadmap.fk_region"-->
<!--              :options="regions"-->
<!--              label="Fk regions"-->
<!--          />-->
          <div>
            <CButton color="primary" class="mr-2" @click="update()">Save</CButton>
            <CButton color="primary" class="mr-2" @click="goBack">Back</CButton>
          </div>
        </CCardBody>
      </CCard>
    </CCol>
  </CRow>
</template>

<script>
import axios from 'axios'
export default {
  name: 'EditUser',
  props: {
    caption: {
      type: String,
      default: 'User id'
    },
  },
  data: () => {
    return {
      roadmap: {
        fk_client: null,
        fk_user: null,
        fk_promo_material: null,
        description: '',
        // applies_to_date: '',


      },
      clients: [],
      users: [],
      promoMaterials: [],
      message: '',
      dismissSecs: 7,
      dismissCountDown: 0,
    }
  },
  methods: {
    goBack() {
      this.$router.go(-1)
      // this.$router.replace({path: '/users'})
    },
    update() {
      let self = this;
      axios.post(  this.$apiAdress + '/api/roadmaps/' + self.$route.params.id + '?token=' + localStorage.getItem("api_token"),
          {
            _method: 'PUT',
            fk_client:            self.roadmap.fk_client,
            fk_user:          self.roadmap.fk_user,
            fk_promo_material:            self.roadmap.fk_promo_material,
            description:          self.roadmap.description,
            applies_to_date:          self.roadmap.applies_to_date,
          })
          .then(function (response) {
            self.message = 'Successfully updated client.';
            self.showAlert();
          }).catch(function (error) {
        if(error.response.data.message == 'The given data was invalid.'){
          self.message = '';
          for (let key in error.response.data.errors) {
            if (error.response.data.errors.hasOwnProperty(key)) {
              self.message += error.response.data.errors[key][0] + '  ';
            }
          }
          self.showAlert();
        }else{
          console.log(error);
          self.$router.push({ path: '/login' });
        }
      });
    },
    showAlert () {
      this.dismissCountDown = this.dismissSecs
    },
  },
  mounted: function(){

    // this.getRegions();
    let self = this;
    axios.get(  this.$apiAdress + '/api/roadmaps/' + self.$route.params.id + '/edit?token=' + localStorage.getItem("api_token"))
        .then(function (response) {
          self.roadmap = response.data.roadmap;
          self.clients = response.data.clients;
          self.regions = response.data.regions;
          self.users = response.data.users;

        }).catch(function (error) {
      console.log(error);
      self.$router.push({ path: '/login' });
    });
  }
}

/*
      items: (id) => {
        const user = usersData.find( user => user.id.toString() === id)
        const userDetails = user ? Object.entries(user) : [['id', 'Not found']]
        return userDetails.map(([key, value]) => {return {key: key, value: value}})
      },
*/

</script>
