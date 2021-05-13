<template>
  <CRow>
    <CCol col="12" lg="6">
      <CCard>
        <CCardBody>
          <h3>
            Edit Client id:  {{ $route.params.id }}
          </h3>
          <CAlert
              :show.sync="dismissCountDown"
              color="primary"
              fade
          >
            ({{dismissCountDown}}) {{ message }}
          </CAlert>
          <CInput label="Name" type="text" placeholder="Name" v-model="client.company_name"/>
          <CInput label="Client name" type="text" placeholder="Client name" v-model="client.client_name"></CInput>
          <CInput label="address" type="text" placeholder="Company address" v-model="client.address"></CInput>
          <CInput label="Client city" type="text" placeholder="Client city" v-model="client.city"></CInput>
          <CInput label=" phone" type="text" placeholder="Company phone" v-model="client.phone"></CInput>
          <CInput label="Client facebook" type="text" placeholder="Client facebook" v-model="client.facebook"></CInput>
          <CInput label="Company instagram" type="text" placeholder="Company instagram" v-model="client.instagram"></CInput>
          <CInput label="Client mail" type="text" placeholder="Client mail" v-model="client.mail"></CInput>
<!--          <CInput label="lat" type="text" placeholder="lat" v-model="client.lat"></CInput>-->
<!--          <CInput label="long" type="text" placeholder="long" v-model="client.long"></CInput>-->
          <CInput
              label="Description"
              placeholder="Description.."
              textarea="true"
              rows="9"
              v-model="client.description"
          />
          <CSelect
              :value.sync="client.fk_user"
              :options="users"
              label="Fk users"
          />
          <CSelect
              :value.sync="client.fk_region"
              :options="regions"
              label="Fk regions"
          />
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
      client: {
        company_name: '',
        client_name: '',
        address: '',
        city: '',
        phone: '',
        facebook: '',
        instagram: '',
        mail: '',
        lat: '',
        long: '',
        description: '',
        fk_user: null,
        fk_region: null,

      },
      // region: '',
      regions: [],
      users: [],
      // statuses: [],
      message: '',
      dismissSecs: 7,
      dismissCountDown: 0,
    }
  },
  methods: {
    // getRegions: function(){
    //   axios.get(  this.$apiAdress + '/api/getRegions?token=' + localStorage.getItem("api_token") )
    //       .then(function (response) {
    //         this.regions = response.data;
    //       }.bind(this));
    // },
    goBack() {
      this.$router.go(-1)
      // this.$router.replace({path: '/users'})
    },
    update() {
      let self = this;
      axios.post(  this.$apiAdress + '/api/clients/' + self.$route.params.id + '?token=' + localStorage.getItem("api_token"),
          {
            _method: 'PUT',
            company_name:            self.client.company_name,
            client_name:          self.client.client_name,
            address:            self.client.address,
            city:          self.client.city,
            phone:            self.client.phone,
            facebook:          self.client.facebook,
            instagram:            self.client.instagram,
            mail:          self.client.mail,
            lat:            self.client.lat,
            long:          self.client.long,
            description:            self.client.description,
            fk_user:          self.client.fk_user,
            fk_region:            self.client.fk_region,
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
    axios.get(  this.$apiAdress + '/api/clients/' + self.$route.params.id + '/edit?token=' + localStorage.getItem("api_token"))
        .then(function (response) {
          self.client = response.data.client;
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
