// When you have a list of items you want to display to the user and you wish to be able to increase that list,
// The best thing to use is an adapter. An adapter takes each item in your list and displays to the user in the style you want
public class VideoListAdapter extends BaseAdapter {

    Fragment fragment;
    ArrayList<VideoDetails> videoDetailsArrayList;
    LayoutInflater inflater;

   // this function counts the number of items in your list so the adapter knows how many items it has to display
    @Override
    public int getCount() {
        return this.videoDetailsArrayList == null ? 0 : this.videoDetailsArrayList.size();
    }

    // When you need to see a particular item, the item is gotten using this function
    //since there are many items, each item has a unique position like position 1 or 2 etc
    //  so when u select the item, the get item memorizes that position number so it can fetch it from the list
    @Override
    public Object getItem(int position) {
        return this.videoDetailsArrayList.get(position);
    }

    // This is where you decide which page you want your list to display on and which data you want to display on the list
    @Override
    public View getView(int position, View convertView, ViewGroup parent) {
        if(inflater == null){
            inflater = this.fragment.getLayoutInflater();
        }

        if (convertView == null){
            convertView = inflater.inflate(R.layout.video_row, null);
        }

        ImageView imageView = convertView.findViewById(R.id.imageView);
        TextView title = convertView.findViewById(R.id.mytitle);
        RelativeLayout linearLayout = convertView.findViewById(R.id.root);
        final VideoDetails videoDetails = this.videoDetailsArrayList.get(position);

        Picasso.get().load(videoDetails.getUrl())
                .placeholder(R.drawable.video_placeholder)
                .fit()
                .centerCrop()
                .into(imageView);

        title.setText(videoDetails.getTitle());

        // this listener is like a waiter waiting to take your order when u click on something on the list,
        //This listener checks if there is network connection, if yes, it sticks a sticker on your page with your request(video)
        //  if no connection it displays an alert to you
        linearLayout.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if(isNetworkAvailable(fragment.getActivity())) {
                    openFragment(new PlayVideoFragment(), v, "videoId" , videoDetails.getVideoId());
                } else {
                    noInternetDialog(fragment.getContext());
                }
            }
        });

        return convertView;
    }

}


public class TabAdapter extends FragmentStatePagerAdapter {

    int TabCount;


    public TabAdapter(FragmentManager fm, int TabCount) {
        super(fm);
        this.TabCount = TabCount;
    }

    @Override
    public Fragment getItem(int i) {
        switch (i){
            case 0:
                MemeFragment memeFragment = new MemeFragment();
                return memeFragment;
            case 1:
                HomeFragment homeFragment = new HomeFragment();
                return homeFragment;
//            case 2:
//                GifFragment gifFragment = new GifFragment();
//                return gifFragment;
//            case 3:
//                FavouriteFragment favouriteFragment = new FavouriteFragment();
//                return favouriteFragment;

            default:
                return null;
        }
    }
}

   // Here we try to get an image from the assets folder  and display to user
// if we do not suceed to get the image, we display an error to say what happened
    try {

        InputStream ims = context.getAssets().open("memes/" + GalImages[position]);
        //Drawable d = Drawable.createFromStream(ims, null);
        Bitmap bitmap = BitmapFactory.decodeStream(ims);
        imageView.setImageBitmap(bitmap);

    } catch (IOException e) {
        e.printStackTrace();
    }
    

    @Override
    public void destroyItem(ViewGroup container, int position, Object object) {
        container.removeView((LinearLayout)object);
    }

    // Here we try to get the image and convert it to a bitmap format, then send the bitmap for processing
    public Bitmap imageUri(int position){
        InputStream ims = null;
        try {
            ims = context.getAssets().open("memes/" + GalImages[position]);
            Bitmap bitmap = BitmapFactory.decodeStream(ims);

            return bitmap;
        } catch (IOException e) {
            e.printStackTrace();
            return null;
        }
    }


// The functions below are from a helper class i created. This functions are used throughout the application.

//This function checks if your phone is connected to the internet when the app asks the question.
//If it is connected, the function returns true else it returns false
public static boolean isNetworkAvailable(Activity activity) {
        ConnectivityManager connectivityManager
                = (ConnectivityManager) activity.getSystemService(Context.CONNECTIVITY_SERVICE);
        NetworkInfo activeNetworkInfo = connectivityManager.getActiveNetworkInfo();
        if (activeNetworkInfo != null && activeNetworkInfo.isConnected() ){
            return true ;
        } else {
            return false;
        }
    }


// This function just displays an alert to the user when his phone is not connected to the internet
// telling them to check internet connection
    public static void noInternetDialog(Context context){
        new AlertDialog.Builder(context)
                .setTitle("No Internet Connection!")
                .setMessage("Check your internet connection and try again")

                // A null listener allows the button to dismiss the dialog and take no further action.
                .setNegativeButton(android.R.string.ok, null)
                .setIcon(android.R.drawable.ic_dialog_alert)
                .show();
    }

// This function is responsible for opening  different fragements
// Fragments are like a ;arge sticker on a chart which can be changed depending on what the user wants to see 
    public static void openFragment(Fragment fragmentname, View view, String stringName, String data) {

        AppCompatActivity activity = (AppCompatActivity) view.getContext();
        Fragment fragment = fragmentname;
        FragmentManager fragmentManager = activity.getSupportFragmentManager();
        FragmentTransaction fragmentTransaction = fragmentManager.beginTransaction();

        Bundle args = new Bundle();
        args.putString(stringName, data);
        fragment.setArguments(args);

        fragmentTransaction.replace(R.id.fragment_holder, fragment);
        fragmentTransaction.commit();
    }

// This function is responsible for opening different pages of the application
    public static void openActivity(Context pageContext, Class cls, String stringName, String data) {

        Intent intent = new Intent(pageContext, cls);
        intent.putExtra(stringName, data);
        pageContext.startActivity(intent);
        ((Activity) pageContext).overridePendingTransition(0,0);
    }

// This function is handles sharing the application. In case you will like to share the application with friends, all that is done with this function
    public static Intent setupShareIntent(Activity activity) {
        ApplicationInfo app = activity.getApplicationContext().getApplicationInfo();
        String filePath = app.sourceDir;

        Intent intent = new Intent(Intent.ACTION_SEND);

        
        intent.setType("*/*");

        // Append file and send Intent
        File originalApk = new File(filePath);

        intent.putExtra(Intent.EXTRA_STREAM, Uri.fromFile(originalApk));

        return intent;

    }
