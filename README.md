# Projects Functionality via `functions.php`

This setup extends your WordPress theme (e.g., Twenty Twenty-Five) by injecting advanced functionality directly into `functions.php`.

It adds a custom post type `Projects`, related taxonomy `Project Type`, archive pagination, AJAX filtering, demo content generation, and external API integrations — all compatible with Full Site Editing (FSE).

---

## 🧩 What It Adds

- ✅ Registers **Projects** custom post type
- ✅ Registers **Project Type** taxonomy
- ✅ Custom archive page with **6 projects per page** and **Next/Prev** buttons
- ✅ AJAX endpoint:
  - 3 latest “Architecture” projects for guests
  - 6 for logged-in users
- ✅ Adds 13 **demo projects**
- ✅ Integrates:
  - **Kanye Rest API** — 5 quotes, its shortcode created
  - **Random Coffee API** — coffee image generator, its function created
- ✅ Adds Admin menu under "Projects" for easy post management

- ajax endpoint can be tested here , {website}/wp-admin/admin-ajax.php?action=get_architecture_projects



