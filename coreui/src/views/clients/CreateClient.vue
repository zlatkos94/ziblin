
<template>
  <CRow>
    <CCol col="12" lg="6">
      <CCard no-header>
        <CCardBody>
          <h3>
            Create Client
          </h3>
          <CAlert
              :show.sync="dismissCountDown"
              color="primary"
              fade
          >
            ({{dismissCountDown}}) {{ message }}
          </CAlert>
          <CInput label="Company name" type="text" placeholder="Company name" v-model="client.company_name"></CInput>
          <CInput label="Client name" type="text" placeholder="Client name" v-model="client.client_name"></CInput>
          <CInput label="address" type="text" placeholder="Company address" v-model="client.address"></CInput>
          <CInput label="Client city" type="text" placeholder="Client city" v-model="client.city"></CInput>
          <CInput label=" phone" type="text" placeholder="Company phone" v-model="client.phone"></CInput>
          <CInput label="Client facebook" type="text" placeholder="Client facebook" v-model="client.facebook"></CInput>
          <CInput label="Company instagram" type="text" placeholder="Company instagram" v-model="client.instagram"></CInput>
          <CInput label="Client mail" type="text" placeholder="Client mail" v-model="client.mail"></CInput>
          <CInput label="lat" type="text" placeholder="lat" v-model="client.lat"></CInput>
          <CInput label="long" type="text" placeholder="long" v-model="client.long"></CInput>
          <CInput textarea="true" label="description" :rows="9" placeholder="description.." v-model="client.description"></CInput>

          <CSelect
              label="Regions"
              :value.sync="client.fk_region"
              :plain="true"
              :options="regions"
          >
          </CSelect>


          <!--          <CInput label="fk_user" type="number" placeholder="fk_user" v-model="client.fk_user"></CInput>-->
<!--          <CInput label="fk_region" type="number" placeholder="fk_region" v-model="client.fk_region"></CInput>-->
<!--          <div class="form-group">-->
<!--            <label>Select Region:</label>-->

<!--            <select class='form-control' v-model='region'>-->
<!--              <option value='0' disabled >Select Region</option>-->
<!--              <option v-for="data in regions" :value="data.id">{{ data.name }}</option>-->
<!--            </select>-->
<!--          </div>-->

          <CButton color="primary" @click="store()">Create</CButton>
          <CButton color="primary" @click="goBack">Back</CButton>
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
      // region: 0,
      // regions: [],
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
        fk_user: 1,
        fk_region: 1,
      },
      regions: [],
      message: '',
      dismissSecs: 7,
      dismissCountDown: 0,
      showDismissibleAlert: false
    }
  },
  methods: {
    getRegions: function(){
      axios.get(  this.$apiAdress + '/api/getRegions?token=' + localStorage.getItem("api_token") )
          .then(function (response) {
            this.regions = response.data;
          }.bind(this));
    },
  created: function(){
    this.getRegions()
  },
    goBack() {
      this.$router.go(-1)
      // this.$router.replace({path: '/users'})
    },
    store() {
      let self = this;
      axios.post(  this.$apiAdress + '/api/clients?token=' + localStorage.getItem("api_token"),
          self.client
      )
          .then(function (response) {
            self.client = {
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
              // fk_user: '',
              fk_region: null,
            };
            self.message = 'Successfully created client.';
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
          self.$router.push({ path: 'login' });
        }
      });
    },
    countDownChanged (dismissCountDown) {
      this.dismissCountDown = dismissCountDown
    },
    showAlert () {
      this.dismissCountDown = this.dismissSecs
    },
  },
  mounted: function(){
    this.getRegions();
    let self = this;
    axios.get(  this.$apiAdress + '/api/clients/create?token=' + localStorage.getItem("api_token"))
        .then(function (response) {
          self.regions = response.data;
        }).catch(function (error) {
      console.log(error);
      // self.$router.push({ path: 'login' });
    });
  }
}

</script>

<style src="vue-advanced-search/dist/AdvancedSearch.css"></style>
