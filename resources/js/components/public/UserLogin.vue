<script setup>
import { UserService } from "@root/services/user/UserService";
import { useUserStore } from "@root/stores/user";
import { FormInput, SubmitButton } from "@admin/common/form";
import { useRouter } from "vue-router";
import { Form } from "vee-validate";
const router = useRouter();
const userStore = useUserStore();
function login(values) {
    values.type = "patron";
    UserService.login({ values }, (res) => {
        userStore.setUser(res.data);
        router.push({ name: "opac-user-summary" });
    });
}
</script>
<template>
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-6">
            <div id="opac-auth" class="maincontent">
                <h1>Log in to your account</h1>
                <Form @submit="login">
                    <input
                        type="hidden"
                        name="koha_login_context"
                        value="opac"
                    />
                    <div class="local-login">
                        <fieldset class="brief">
                            <FormInput
                                name="login_key"
                                label="Card number or username:"
                                :labelInline="false"
                            />
                            <FormInput
                                name="password"
                                label="Password:"
                                type="password"
                                :labelInline="false"
                            />
                            <fieldset class="action pt-0">
                                <SubmitButton
                                    name="Log in"
                                    :submitting="UserService.submitting.value"
                                    :cancel="false"
                                    class="btn btn-primary btn-custom"
                                />
                            </fieldset>
                        </fieldset>
                    </div>
                    <div id="nologininstructions">
                        <div id="OpacLoginInstructions">
                            <div class="default_item">
                                <div class="default_body">
                                    New patrons, please see staff for
                                    assistance.<br />Returning patrons, don't
                                    hesitate to reach out if you need help!
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <p>
                            <a href="/cgi-bin/koha/opac-password-recovery.pl"
                                >Forgot your password?</a
                            >
                        </p>
                    </div>
                    <div id="registrationinstructions">
                        <p>
                            <a href="/cgi-bin/koha/opac-memberentry.pl"
                                >Create an account</a
                            >
                        </p>
                    </div>
                </Form>
            </div>
        </div>
    </div>
</template>
