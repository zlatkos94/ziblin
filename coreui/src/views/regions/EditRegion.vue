<template>
  <CRow>
    <CCol col="12" lg="6">
      <CCard>
        <CCardBody>
          <h3>
            Edit Region id:  {{ $route.params.id }}
          </h3>
          <CAlert
              :show.sync="dismissCountDown"
              color="primary"
              fade
          >
            ({{dismissCountDown}}) {{ message }}
          </CAlert>
          <CInput label="Name" type="text" placeholder="Name" v-model="region.name"/>
          <CInput
              label="Description"
              placeholder="Description.."

              textarea="true"
              rows="9"
              v-model="region.description"
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
      region: {
        name: '',
        description: '',
      },
      statuses: [],
      message: '',
      dismissSecs: 2,
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
      axios.post(  this.$apiAdress + '/api/regions/' + self.$route.params.id + '?token=' + localStorage.getItem("api_token"),
          {
            _method: 'PUT',
            name:            self.region.name,
            description:          self.region.description,
          })
          .then(function (response) {
            self.message = 'Successfully updated region.';
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
    let self = this;
    axios.get(  this.$apiAdress + '/api/regions/' + self.$route.params.id + '/edit?token=' + localStorage.getItem("api_token"))
        .then(function (response) {
          self.region = response.data.region;
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
