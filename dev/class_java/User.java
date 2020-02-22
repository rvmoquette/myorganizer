
public class User {

    // key
    private int code_user;

    // dependences

    // informations
    private String user_Login;
    private String user_Password;
    private String user_Email;
    private boolean user_Admin;

    public User() { }

    public User( int code_user,  String user_Login,  String user_Password,  String user_Email,  boolean user_Admin ) {
        this.code_user = code_user;
        this.user_Login = user_Login;
        this.user_Password = user_Password;
        this.user_Email = user_Email;
        this.user_Admin = user_Admin;
    }

    public int get_code_user() { return this.code_user; }
    public String get_user_Login() { return this.user_Login; }
    public String get_user_Password() { return this.user_Password; }
    public String get_user_Email() { return this.user_Email; }
    public boolean get_user_Admin() { return this.user_Admin; }

    public void set_user_Login( String user_Login ) { this.user_Login = user_Login; }
    public void set_user_Password( String user_Password ) { this.user_Password = user_Password; }
    public void set_user_Email( String user_Email ) { this.user_Email = user_Email; }
    public void set_user_Admin( boolean user_Admin ) { this.user_Admin = user_Admin; }

}
