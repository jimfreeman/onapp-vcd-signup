<?php

return [
    'onapp_protocol' => 'https', // select either http or https
    'onapp_url' => 'xxx', // the url of your onapp instance. eg. cp.onapp.test 
    'onapp_username' => 'xxx', // username of an OnApp user with administrator permissions
    'onapp_password' => 'xxx', // the password for the above user
    'onapp_compute_resource_id' => 0, // the compute resource ID of your vCloud Director instance
    'onapp_bucket_id' => 0, // the bucket ID you want to use for newly created accounts
    'onapp_role_id' => 0, // the role ID of the role you want new users to be created as. Recommended: 'vCloud Organization Administrator'
];
