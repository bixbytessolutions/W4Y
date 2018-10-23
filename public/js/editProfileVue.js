let token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    Vue.http.interceptors.push(function(request, next) {
        request.headers.set('X-CSRF-TOKEN', token.content)
        next();
      })
} 
Vue.use(window.vuelidate.default)

var required=window.validators.required;
var sameAs=window.validators.sameAs;
var regexhelpers=window.validators.helpers.regex;
var email=window.validators.email;
var minLength=window.validators.minLength;
var numeric=window.validators.numeric;
var url=window.validators.url;
var pwdRegx = regexhelpers('pwdRegx', /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?&])[A-Za-z\d$@$!%*?&]{6,}/)
var phoneRegx = regexhelpers('phoneRegx',/^\d{10,14}$/)

var editProfileApp= new Vue({
    el:'#editProfile',
    data: {
        urlPrefix: "/",
        skills:[],
        modalAction:'',
        userData:{
            title:"Mr",
            country:"Switzerland",
            dob:''
        },
        isCompany:false,
        adminRoleId:16,
        readOnly:15,
        projectAuthor:17,
        companyUserData:{
            role_id:"15",
            country:"Switzerland",
        },
        employeeData:{
            first_name:null,
            last_name:null,
            email:null,
            profile_title:null,
            role_id:null,
            companyid:false,
          
        },
        employeeEmail:null,
        userRoles:{ },
        selectedFile:null,  
        allSkills:[],
        certificates:[],
        usercertificates:{
            certification_name: null,
            certification_desc: null,
            certification_file:null,
        
        },

        educations:[],
        userEducations:{
            education_title:null,
            education_schoolname:null,
            education_desc:null,
            education_start_date:null,
            education_completiondate:null,
        
        },
        portfolios:{
            total: 0,
            per_page: 2,
            from: 1,
            to: 0,
            current_page: 1
        },
        userPortfolios:{
            portfolio_title:null,
            portfolio_url:null,
            portfolio_description:null,
            portfolio_start_date:null,
            portfolio_completiondate:null,
            portfolio_portfolioimg:'',
        }, 
        offset: 9,
        pagination:[],
        pageNumbers:[],
        passwordNew: '',
        passwordConfirm: '',
        loading:false,
        successMsg:false,
        updateprofileMsg:false,
        updateemailerror:false,
        successMsgCerctifiacte:false,
        updateMsgCerctifiacte:false,
        successMsgEducation:false,
        updateMsgEducation:false,
        successMsgPortfolio:false,
        updateMsgPortfolio:false,
        deleteMsgPortfolio:false,
        successMsgEmployee:false,
        updateMsgEmployee:false,
        addEmailerror:false,
        successInvite:false,
        errorInvite:false,
        loadingInvite:false,
    },

    validations: {
        passwordNew: {
          required:required,
          pwdRegx:pwdRegx,
        },
        passwordConfirm: {
            required:required,
            sameAsPassword: sameAs('passwordNew')
          },
          userData:{
              first_name:{
                required: required,
              },
              email:{
                required: required,
                email:email,
              },
              phone_no:{
                phoneRegx:phoneRegx,
              },
              zip:{
                numeric:numeric,
              }

          },

          usercertificates:{
            certification_name:{
                required: required,
            },
            certification_desc:{
                required: required,
            },
            
        },

        userEducations:{
            education_title:{
                required: required,
            },
            education_schoolname:{
                required: required,
            },
            education_desc:{
                required: required,
            },
            education_start_date:{
                required: required,
            },
            education_completiondate:{
                required: required,
            },
        

        },
        userPortfolios:{
            portfolio_title:{ required: required  },
            portfolio_url:{ required: required, url:url, },
            portfolio_description:{  required: required, },
            portfolio_start_date:{  required: required, },
            portfolio_completiondate:{  required: required, },
            portfolio_portfolioimg:{  required: required, },
        }, 
        employeeData:{
            first_name:{ required: required },
            last_name:{ required: required },
            email:{required: required, email:email },
            profile_title:{required: required},
             
        }, 
        employeeEmail:{
            email:email,
        }


      },
   
    
    created: function(){
        this.loadUserRoles();
        this.fetchUserDetail();
        this.fetchUserCertificates();
        this.fetchUserportfolios();   

    },
    computed : function(){
      this.pageNumbers = this.pagesNumber();
     
    },
    
    mounted:function(){
        // this.loadCssDropdown();
        this.addDatePicker();
    },
    
    methods:{
        status:function(validation) {
            return {
                error: validation.$error,
                dirty: validation.$dirty,
            }
        },
  
        fetchUserDetail:function(){
            this.$http.get(this.urlPrefix+"fetchUserDetail").then(function(response){
                this.userData = response.data;
                var title = this.userData.title;
                var country = this.userData.country;
                if(this.userData.company!=0){
                    this.isCompany=true;
                    this.fetchCompanyUserDetail(this.userData.companyid);
                    
                    //this.loadUserRoles();
                }else{
                    if(this.userData.companyid !=0){
                        
                        this.fetchCompanyUserDetail(this.userData.companyid); 
                      //  this.loadUserRoles();
                    }
                   
                    this.fetchAllSkills();
                    this.fetchUserEductions();
                    this.fetchUserSkills();
                }
              
              
                $('#title.csdropdown .select-items div').each(function(){
                    
                if(title == $(this).text()){
                        $(this).click();
                    }
                })

                $('#country.csdropdown .select-items div').each(function(){
                    if(country!=''){
                        if(country == $(this).text()){
                            $(this).click();
                        }
                    }
                })

                $('#successmsgUpdate').delay(3000).fadeOut(350);
            
            });
        },

    
        
        updateUserData: function(){
            this.userData.title = $("#title select").val();
            this.userData.country = $('#country select').val();
            this.userData.description = $('#description').html();
            this.userData.userId = $('#userId').val();
            this.userData.first_name   = $('.first_name').val();
            this.userData.last_name = $('.last_name').val();
            this.userData.street = $('.street').val();
            this.userData.zip = $('.zip').val();
            this.userData.dob = $('#date').val();
            this.userData.city = $('.city').val();
            this.userData.phone = $('.phone').val();
            this.userData.email = $('.email').val();
            this.$http.post(this.urlPrefix+'updateUserData',{ user:this.userData }).then(
                function(response){
                    $('#successmsgUpdate').fadeIn(350);
                   
                    this.updateprofileMsg=true;
                    this.updateemailerror=false;
                    this.fetchUserDetail();
                }).catch(function(error){
                    this.updateprofileMsg=false;
                  
                    $('html, body').animate({
                        scrollTop: $("#saveProfileTop").offset().top
                    }, 2000);

                    this.updateemailerror=true;

                });
            
        },

        uploadFile: function(){
            this.createImage($('#profile_file')[0].files[0])
        },

        createImage: function(file){
            let formData = new FormData();
            formData.append('uploadFile', file);
           
            this.$http.post(this.urlPrefix+'updateprofilePhoto',formData,{
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
              }).then(
                function(response){
                 this.fetchUserDetail();
                }
            )
            
        }, 

        changePassword:function(){
            if(this.passwordConfirm != '' ){
                this.$http.post(this.urlPrefix+"changepassword",{ passwordNew:this.passwordNew,passwordConfirm:this.passwordConfirm })
                .then(function(response){
                    if(response.status==200){
                        this.successMsg=true;
                     }
                });
            }
            else{
            this.$v.passwordNew.$touch();
            }
        },

        fetchCompanyUserDetail:function(companyId){
            this.$http.post(this.urlPrefix +"fetchcompanyuserdata",{ companyId:companyId }).then(function(response){
            this.companyUserData = response.data;
            //this.loadCssDropdown();
            })

        },

        updateCompanyUserData:function(){
            this.userData.country = $('#country select').val();
            this.userData.description = $('#description').html();
            this.userData.first_name   = $('.first_name').val();
            this.userData.last_name = $('.last_name').val();
            this.userData.zip = $('.zip').val();
            this.userData.city = $('.city').val();
            this.userData.phone = $('.phone').val();
            this.userData.email = $('.email').val();
            
            this.$http.post(this.urlPrefix+'updatecompanyuserdata',{ user:this.userData }).then(
                function(response){
                    $('#successmsgUpdate').fadeIn(350);
                    this.updateprofileMsg=true;
                    this.updateemailerror=false;
                    this.fetchUserDetail();
                }).catch(function(error){
                    this.updateprofileMsg=false;
                
                    $('html, body').animate({
                        scrollTop: $("#saveProfileTop").offset().top
                    }, 2000);

                    this.updateemailerror=true;

                });


        },

        loadUserRoles:function(){
         this.$http.get(this.urlPrefix+'loaduserroles').then(function(response){
            this.userRoles=response.data;
            self =this;
            setTimeout(function(){
                self.loadCssDropdown();
            },1000)
         });
        },
          
        loadComapnyUsers :function(item){
            this.modalAction='add';
            this.employeeData.first_name="";
            this.employeeData.last_name="";
            this.employeeData.email="";
            this.employeeData.profile_title="";
            this.employeeData.name="";
            this.employeeData.companyid=this.userData.companyid;
            this.employeeData.id="";
           

           if(item !== null){
               
               
                this.modalAction='edit';
                this.employeeData.first_name=item.first_name;
                this.employeeData.last_name=item.last_name;
                this.employeeData.email=item.email;
                this.employeeData.profile_title=item.profile_title;        
                this.employeeData.name=item.name;
                this.employeeData.role_id=item.role_id;
                this.employeeData.companyid=item.companyid;
                this.employeeData.id=item.empId;
                $('#roles.csdropdown .select-items div').each(function(){
                    if(item.name == $(this).text()){
                            $(this).click();
                        }
                })
              
            }
        
               this.$v.$reset();  
               this.successMsgEmployee=false;
               this.updateMsgEmployee=false;
              // this.loadCssDropdown();

        },

        addCompanyUser:function(){
          let cmpName=this.userData.first_name +' '+ this.userData.last_name; 
          this.employeeData.role_id=$('#roles.csdropdown select').val();
          if(this.employeeData.first_name != '' && this.employeeData.last_name != '' && this.employeeData.email!='' &&  this.employeeData.profile_title!='' ){
          this.$http.post(this.urlPrefix+'addempolyee',{employeeData:this.employeeData}, {
            before: function() {
                $('#successmsgUpdate').fadeIn(350);
                this.loading = true;
                this.successMsgEmployee=false;
                this.addEmailerror=false;
            }
            }).then(function(response){
                this.fetchCompanyUserDetail(this.userData.companyid); 
                this.loading = false;
                this.addEmailerror=false;
                this.successMsgEmployee=true;
            }).catch(function(error){
                this.loading = false;
                this.addEmailerror=true;

            }).then(function(){
                $('#successmsgUpdate').delay(3000).fadeOut(350);
            });
          }
        else{
            this.$v.employeeData.$touch();
        }
        },

        updateEmployee:function(){
            let cmpName=this.userData.first_name +' '+ this.userData.last_name; 
            this.employeeData.role_id=$('#roles.csdropdown select').val();
            if(this.employeeData.first_name != '' && this.employeeData.last_name != '' && this.employeeData.email!='' &&  this.employeeData.profile_title!='' ){
                this.$http.post(this.urlPrefix+"updateemployee",{ employeeData :this.employeeData, cmpName:cmpName  }).then(function(response){
                    this.fetchCompanyUserDetail(this.userData.companyid); 
                    this.updateMsgEmployee=true;
                });
             }
            else
            {
                this.$v.employeeData.$touch();
                this.updateMsgEmployee=false;
            }
        },

        deleteEmployee:function () {
            this.$http.post(this.urlPrefix+"deleteemployee",{ empId : this.employeeData.id }).then(function(response){
                this.fetchCompanyUserDetail(this.userData.companyid);

            });

          },

          inviteEmployee:function(){
          
            let cmpName=this.userData.first_name +' '+ this.userData.last_name; 
            this.$http.post(this.urlPrefix+"inviteemployee",{ employeeEmail : this.employeeEmail, cmpName:cmpName,cmpId:this.userData.companyid},{
            before: function() {
                $('#successmsgUpdate').fadeIn(350);
                this.loadingInvite = true;
                this.errorInvite=false;
                this.successInvite=false;
            }
            }).then(function(response){
                this.errorInvite=false;
                this.loadingInvite = false;
                this.successInvite=true;
                
            }).catch(function(error){
                this.loadingInvite = false;
                this.successInvite=false;
                this.errorInvite=true;
               
            }).then(function(){
                $('#successmsgUpdate').delay(3000).fadeOut(350);
            });
          
          },
       

        fetchAllSkills:function(){
           
            this.$http.get(this.urlPrefix+"fetchAllSkills").then(function(response){
                this.allSkills = response.data;
                //console.log(this.allSkills);
                skillsSet = $('#skillInput').selectize({
                    persist: false,
                    valueField: 'value',
                    labelField: 'name',
                              createOnBlur: true,
                              create: false,
                    options: this.getOptions(),
                    maxItems: 2
                });

        
            });
    
        },

        getOptions:function(){
            let obj=[];
            this.allSkills.forEach(function(element){
                obj.push({value:element.id,name:element.name})
            });
            return obj; 
        },

        addSkill:function(){
            var skillsObj=skillsSet[0].selectize;

            var skillArr = skillsObj.getValue().split(',');

            this.$http.post(this.urlPrefix+'addtoskill',{ skillArr :skillArr }).then(
                function(response){
                    this.fetchUserSkills();
                }
            )
            $('#skillInput')[0].selectize.removeOption(1)
            
        },
        
        
        fetchUserSkills:function(){
            this.$http.get(this.urlPrefix+"fetchuserskill").then(function(response){
                let self = this;
                self.skills = response.data;
            });
        },

        deleteUserSkill:function(skillId){

            this.$http.post(this.urlPrefix+"deleteskill",{ skillId :skillId }).then(function(response){
                this.fetchUserSkills();
            });
        },

        fetchUserCertificates:function(){
            this.$http.get(this.urlPrefix+"fetchusercertificates").then(function(response){
                this.certificates = response.data;

            });
        },

        saveCertificate: function () {
            this.usercertificates.certification_file= $('#fileUpload')[0].files[0];
           let formData= new FormData();
           formData.append('certification_name',this.usercertificates.certification_name);
           formData.append('certification_desc',this.usercertificates.certification_desc);
           formData.append('uploadFile',this.usercertificates.certification_file);
           if(this.usercertificates.certification_name != '' && this.usercertificates.certification_desc != '' ){
            this.$http.post(this.urlPrefix+"addusercertificates", formData,
            {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            },
            { before: function() {
            $('#successmsgUpdate').fadeIn(350);
            this.successMsgCerctifiacte=false;
            }
            }).then(function(response){
                this.successMsgCerctifiacte=true;
                this.fetchUserCertificates();
                this.usercertificates.certification_name="";
                this.usercertificates.certification_desc="";
                this.usercertificates.certification_file="";
                $('#fileUpload').val('');
                this.$v.$reset();  
             
            }).then(function(){
                $('#successmsgUpdate').delay(3000).fadeOut(350);
            });
        }
        else{

            this.$v.usercertificates.$touch();
        }

        },
        loadCertificates :function(item){
            this.modalAction='add';
            this.usercertificates.certification_name="";
            this.usercertificates.certification_desc="";
            this.usercertificates.certification_file="";
           if(item !== null){
                this.modalAction='edit';
                this.usercertificates.certification_name=item.title;
                this.usercertificates.certification_desc=item.description;
                this.usercertificates.certification_file=item.portfolioimg;
                this.usercertificates.certification_id=item.id;
              
            }
            this.$v.$reset();  
            this.successMsgCerctifiacte=false;  
            this.updateMsgCerctifiacte=false;  

        },

        updateCertificate:function(){

            if($('#fileUpload')[0].files[0])
            {
            this.usercertificates.certification_file= $('#fileUpload')[0].files[0];
            }
            let formData= new FormData();
            formData.append('certification_name',this.usercertificates.certification_name);
            formData.append('certification_desc',this.usercertificates.certification_desc);
            formData.append('uploadFile',this.usercertificates.certification_file);
            formData.append('certification_id',this.usercertificates.certification_id);
            if(this.usercertificates.certification_name != '' && this.usercertificates.certification_desc != '' ){
                this.$http.post(this.urlPrefix+"updatecertificates",formData,{
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
                },
                { before: function() {
                    $('#successmsgUpdate').fadeIn(350);
                    this.updateMsgCerctifiacte=false;
                }
                }).then(function(response){
                        this.fetchUserCertificates();
                        $('#fileUpload').val('');
                        this.updateMsgCerctifiacte=true;  
            
                }).then(function(){
                    $('#successmsgUpdate').delay(3000).fadeOut(350);
                });
            }
            else
            {
                
            this.$v.usercertificates.$touch();

            }
                
        },

        deleteCertificate:function(certId){
            this.$http.post(this.urlPrefix+"deletescertificate",{ certId :certId }).then(function(response){
                this.fetchUserCertificates();
            });
        },

        fetchUserEductions:function(){
            this.$http.get(this.urlPrefix+"fetchusereducations").then(function(response){
                this.educations = response.data;

            });
        },

        loadEducations :function(item){
            this.modalAction='add';
            this.userEducations.education_title="";
            this.userEducations.education_schoolname="";
            this.userEducations.education_desc="";
            this.userEducations.education_start_date="";
            this.userEducations.education_completiondate="";
           
           if(item !== null){
              
                this.modalAction='edit';
                this.userEducations.education_title=item.title;
                this.userEducations.education_schoolname=item.sub_description;
                this.userEducations.education_desc=item.description;
                this.userEducations.education_start_date=item.edit_start_date;
                this.userEducations.education_completiondate=item.edit_completiondate;
                this.userEducations.education_id=item.id;
              
            }
            this.$v.$reset();  
            this.successMsgEducation=false;
            this.updateMsgEducation=false;
        },

        saveEduction: function () {
           
            if(this.userEducations.education_title != '' && this.userEducations.education_schoolname != '' && this.userEducations.education_desc!='' &&  this.userEducations.education_start_date!='' && this.userEducations.education_completiondate!='' ){
            this.$http.post(this.urlPrefix+"addusereducations",{ userEducations :this.userEducations },
            { before: function() {
                $('#successmsgUpdate').fadeIn(350);
                this.successMsgEducation=false;
            }
            }).then(function(response){
                    this.$v.$reset(); 
                    this.fetchUserEductions();
                    this.userEducations.education_title="";
                    this.userEducations.education_schoolname="";
                    this.userEducations.education_desc="";
                    this.userEducations.education_start_date="";
                    this.userEducations.education_completiondate="";
                    this.successMsgEducation=true;
            }).then(function(){
                $('#successmsgUpdate').delay(3000).fadeOut(350);
            });
           }
        else{
            this.$v.userEducations.$touch();
            this.successMsgEducation=false;
         
           }

        },

        updateEducation:function(){
            this.userEducations.education_start_date=$('#FromDate1').val();
            this.userEducations.education_completiondate=$('#untillDate1').val();
            if(this.userEducations.education_title != '' && this.userEducations.education_schoolname != '' && this.userEducations.education_desc!='' &&  this.userEducations.education_start_date!='' && this.userEducations.education_completiondate!='' ){
                this.$http.post(this.urlPrefix+"updateeducation",{ userEducation :this.userEducations },
            { before: function() {
                $('#successmsgUpdate').fadeIn(350);
                this.updateMsgEducation=false;
            }
            }).then(function(response){
                    this.fetchUserEductions();
                    this.updateMsgEducation=true;
                    this.successMsgEducation=false;
            }).then(function(){
                $('#successmsgUpdate').delay(3000).fadeOut(350);
            });
            }
            else
            {
                this.$v.userEducations.$touch();
                this.updateMsgEducation=false;
            }
        },

        deleteEducation:function(eduId){
            this.$http.post(this.urlPrefix+"deleteeducation",{ eduId :eduId }).then(function(response){
                this.fetchUserEductions();
            });
        },

        fetchUserportfolios:function(){
            this.$http.get(this.urlPrefix+"fetchuserportfolios?page="+this.portfolios.current_page).then(function(response){
                this.portfolios = response.data;
                this.pagination = response.data;
                this.pageNumbers = this.pagesNumber();
                $('#successmsgUpdate').delay(3000).fadeOut(350);
                // console.log(this.pagesNumber);
                    
            });
        },

        loadportfolios:function(item){
            this.modalAction='add';
            this.userPortfolios.portfolio_title="";
            this.userPortfolios.portfolio_url="";
            this.userPortfolios.portfolio_description="";
            this.userPortfolios.portfolio_start_date="";
            this.userPortfolios.portfolio_completiondate="";
            this.userPortfolios.portfolio_portfolioimg="portfolio.jpg";
            this.userPortfolios.portfolio_url="";
           
           if(item !== null){
                this.modalAction='edit';
                this.userPortfolios.portfolio_title=item.title;
              
                this.userPortfolios.portfolio_description=item.description;
                this.userPortfolios.portfolio_portfolioimg=item.portfolioimg;
                this.userPortfolios.portfolio_start_date=item.edit_start_date;
                this.userPortfolios.portfolio_completiondate=item.edit_completiondate;
                this.userPortfolios.portfolio_url=item.portfoliourl;
                this.userPortfolios.portfolio_id=item.id;
            }
            this.$v.$reset();  
            this.successMsgPortfolio=false;
            this.updateMsgPortfolio=false;
        },

        saveportfolio:function() {
            if(this.userPortfolios.portfolio_title != '' && this.userPortfolios.portfolio_url  != '' && this.userPortfolios.portfolio_description!='' &&  this.userPortfolios.education_start_date!='' && this.userPortfolios.portfolio_completiondate!='' ){
            this.userPortfolios.portfolio_portfolioimg= $('#reference_file')[0].files[0];
            let formData= new FormData();
            formData.append('portfolio_title',this.userPortfolios.portfolio_title);
            formData.append('portfolio_description',this.userPortfolios.portfolio_description);
            formData.append('portfolio_start_date',this.userPortfolios.portfolio_start_date);
            formData.append('portfolio_completiondate',this.userPortfolios.portfolio_completiondate);
            formData.append('uploadFile', this.userPortfolios.portfolio_portfolioimg);
            formData.append('portfolio_url', this.userPortfolios.portfolio_url);

             this.$http.post(this.urlPrefix+"addportfolio", formData,{
                 headers: {
                     'Content-Type': 'multipart/form-data'
                 }
               },
               { before: function() {
                $('#successmsgUpdate').fadeIn(350);
                this.successMsgPortfolio=false;
               }
                }).then(function(response){
                        this.$v.$reset(); 
                        this.fetchUserportfolios();
                        this.changePage();
                        this.userPortfolios.portfolio_title="";
                        this.userPortfolios.portfolio_description="";
                        this.userPortfolios.portfolio_start_date="";
                        this.userPortfolios.portfolio_completiondate="";
                        this.userPortfolios.portfolio_url="";
                        this.userPortfolios.portfolio_portfolioimg="portfolio.jpg";
                        $('#reference_file').val('');
                        this.successMsgPortfolio=true;
                    
                    }).then(function(){
                        $('#successmsgUpdate').delay(3000).fadeOut(350);
                    });
                    }
                    else{
                        this.$v.userPortfolios.$touch();
                        this.successMsgPortfolio=false;
                    
                    }
                

        },

        updatePortfolio:function(){
            if(this.userPortfolios.portfolio_title != '' && this.userPortfolios.portfolio_url  != '' && this.userPortfolios.portfolio_description!='' &&  this.userPortfolios.education_start_date!='' && this.userPortfolios.portfolio_completiondate!='' ){
            if($('#reference_file')[0].files[0])
            {
                this.userPortfolios.portfolio_portfolioimg= $('#reference_file')[0].files[0];
            }
            let formData= new FormData();
            formData.append('portfolio_title',this.userPortfolios.portfolio_title);
            formData.append('portfolio_description',this.userPortfolios.portfolio_description);
            formData.append('portfolio_start_date',this.userPortfolios.portfolio_start_date);
            formData.append('portfolio_completiondate',this.userPortfolios.portfolio_completiondate);
            formData.append('uploadFile', this.userPortfolios.portfolio_portfolioimg);
            formData.append('portfolio_id',this.userPortfolios.portfolio_id);
            formData.append('portfolio_url', this.userPortfolios.portfolio_url);
            this.$http.post(this.urlPrefix+"updateportfolio",formData,{
            headers: {
                'Content-Type': 'multipart/form-data'
            }
            }, 
            { before: function() {
                $('#successmsgUpdate').fadeIn(350);
                this.updateMsgPortfolio=false;
            }
            }).then(function(response){
                this.fetchUserportfolios();
                $('#reference_file').val('');
                this.updateMsgPortfolio=true;
                this.successMsgPortfolio=false;
            }).then(function(){
                $('#successmsgUpdate').delay(3000).fadeOut(350);
            });
            }
            else
                {
                    this.$v.userPortfolios.$touch();
                    this.updateMsgPortfolio=false;
                }
                
        },

        deletePortfolio:function(portfolioId){
     
           this.$http.post(this.urlPrefix+"deleteeportfolio",{ portfolioId :portfolioId },
           { before: function() {
            $('#successmsgUpdate').fadeIn(350);
            this.deleteMsgPortfolio=false;
           }
           }, { before: function() {
            $('#successmsgUpdate').fadeIn(350);
            this.deleteMsgPortfolio=false;
           }
           }).then(function(response){
            if($('#listNew .childdiv').length==1){
                    this.portfolios.current_page = this.portfolios.current_page - 1;
                }
                $('#successmsgUpdate').fadeIn();
                this.deleteMsgPortfolio=true;
                this.fetchUserportfolios();
           }).then(function(){
            $('#successmsgUpdate').delay(3000).fadeOut(350);
           });
        },

        redirectPortfolio:function(portfolioUrl){
            window.open(portfolioUrl);
        },

        addDatePicker: function(){

            let self = this;
            $("#untillDate1").datepicker({
                dateFormat: "dd.mm.yy",
                onSelect: function(selectedDate){
                    self.userEducations.education_completiondate=$('#untillDate1').val();
                  
                    $( "#FromDate1" ).datepicker( "option", "maxDate", selectedDate);
                }
              });

              $("#untillDate").datepicker({
                dateFormat: "dd.mm.yy",
                onSelect: function(selectedDate){
                  self.userPortfolios.portfolio_completiondate=$('#untillDate').val();
                  $( "#FromDate" ).datepicker( "option", "maxDate", selectedDate);
              }
              });

             $("#FromDate").datepicker({
                dateFormat: "dd.mm.yy",
        
                onSelect: function (selectedDate) {
                    self.userPortfolios.portfolio_start_date=$('#FromDate').val();
                  $( "#untillDate" ).datepicker( "option", "minDate", selectedDate);
                  
                }
              });

              $("#FromDate1").datepicker({
                dateFormat: "dd.mm.yy",
        
                onSelect: function (selectedDate) {
                    self.userEducations.education_start_date=$('#FromDate1').val();
                  $( "#untillDate1" ).datepicker( "option", "minDate", selectedDate);
                  
                }
              });

            
              $("#FromDate").datepicker({
                dateFormat: "dd.mm.yy"
              });
        
        
                $("#date").datepicker({
                  dateFormat: "dd.mm.yy",
                  maxDate: "0" ,
                  changeMonth: true,
                  changeYear: true,
                  yearRange: ' 1970 : * ',
                  
                });

               $(".FromDate").click(function(){
                  $('#FromDate').datepicker("show");
               })
        
               $(".untillDate").click(function(){
                 $('#untillDate').datepicker("show");
               })
        
               $(".FromDate1").click(function(){
                  $('#FromDate1').datepicker("show");
        
               })
        
               $(".untillDate1").click(function(){
        
                 $('#untillDate1').datepicker("show");
               })
        },


        pagesNumber:function() {
            if (!this.pagination.to) {
              return [];
            }
            let from = this.pagination.current_page - this.offset;
            if (from < 1) {
              from = 1;
            }
            let to = from + (this.offset * 2);
            if (to >= this.pagination.last_page) {
              to = this.pagination.last_page;
            }
            let pagesArray = [];
            for (let page = from; page <= to; page++) {
              pagesArray.push(page);
            }
              return pagesArray;
          },
          changePage:function(page) {
            this.pagination.current_page = page;
             this.fetchUserportfolios();
          },
       
        loadCssDropdown:function(){

            var x, i, j, selElmnt, a, b, c;
            /*look for any elements with the class "custom-select":*/
            x = document.getElementsByClassName("csdropdown");
            console.log(x);
            
              for (i = 0; i < x.length; i++) {
              selElmnt = x[i].getElementsByTagName("select")[0];
              /*for each element, create a new DIV that will act as the selected item:*/
              a = document.createElement("DIV");
              a.setAttribute("class", "select-selected");
 
              a.innerHTML = selElmnt.options[selElmnt.selectedIndex].innerHTML;
              x[i].appendChild(a);
              /*for each element, create a new DIV that will contain the option list:*/
              b = document.createElement("DIV");
              b.setAttribute("class", "select-items select-hide");
              for (j = 1; j < selElmnt.length; j++) {
                /*for each option in the original select element,
                create a new DIV that will act as an option item:*/
                c = document.createElement("DIV");
                c.innerHTML = selElmnt.options[j].innerHTML;
                c.addEventListener("click", function(e) {
                    /*when an item is clicked, update the original select box,
                    and the selected item:*/
                    var y, i, k, s, h;
                    s = this.parentNode.parentNode.getElementsByTagName("select")[0];
                    h = this.parentNode.previousSibling;
                    for (i = 0; i < s.length; i++) {
                      if (s.options[i].innerHTML == this.innerHTML) {
                        s.selectedIndex = i;
                        h.innerHTML = this.innerHTML;
                        y = this.parentNode.getElementsByClassName("same-as-selected");
                        for (k = 0; k < y.length; k++) {
                          y[k].removeAttribute("class");
                        }
                        this.setAttribute("class", "same-as-selected");
                        break;
                      }
                    }
                    h.click();
                });
                b.appendChild(c);
              }
              x[i].appendChild(b);
              a.addEventListener("click", function(e) {
                  /*when the select box is clicked, close any other select boxes,
                  and open/close the current select box:*/
                  e.stopPropagation();
                  closeAllSelect(this);
                  this.nextSibling.classList.toggle("select-hide");
                  this.classList.toggle("select-arrow-active");
                });
            }
            function closeAllSelect(elmnt) {
              /*a function that will close all select boxes in the document,
              except the current select box:*/
              var x, y, i, arrNo = [];
              x = document.getElementsByClassName("select-items");
              y = document.getElementsByClassName("select-selected");
              for (i = 0; i < y.length; i++) {
                if (elmnt == y[i]) {
                  arrNo.push(i)
                } else {
                  y[i].classList.remove("select-arrow-active");
                }
              }
              for (i = 0; i < x.length; i++) {
                if (arrNo.indexOf(i)) {
                  x[i].classList.add("select-hide");
                }
              }
            }
            /*if the user clicks anywhere outside the select box,
            then close all select boxes:*/
            document.addEventListener("click", closeAllSelect);
        },


    },

    delimiters: ["<%","%>"]

})





