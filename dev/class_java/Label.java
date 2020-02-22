
public class Label {

    // key
    private int code_label;

    // dependences

    // informations
    private String label_Name;

    public Label() { }

    public Label( int code_label,  String label_Name ) {
        this.code_label = code_label;
        this.label_Name = label_Name;
    }

    public int get_code_label() { return this.code_label; }
    public String get_label_Name() { return this.label_Name; }

    public void set_label_Name( String label_Name ) { this.label_Name = label_Name; }

}
