-- Cartalyst Global seed data
-- Import AFTER schema.sql
--
-- Default admin is created the first time you visit /setup
-- Email:    admin@cartalystglobal.com
-- Password: ChangeMeNow!2026
-- CHANGE THIS PASSWORD IMMEDIATELY after first login.
--
-- If you prefer SQL: generate a hash on the server with
--   php -r "echo password_hash('YourStrongPassword', PASSWORD_DEFAULT);"
-- then INSERT into admins (name, email, password_hash) VALUES (...);

SET NAMES utf8mb4;
USE `cartalyst_global`;

INSERT INTO `settings` (`setting_key`, `setting_value`) VALUES
  ('company_name', 'Cartalyst Global'),
  ('tagline', 'Global procurement and sourcing, delivered with certainty.'),
  ('phone', '+1 (000) 000-0000'),
  ('whatsapp', '+10000000000'),
  ('email', 'hello@cartalystglobal.com'),
  ('address', 'Replace with your registered office address'),
  ('hours', 'Monday–Friday, 09:00–18:00'),
  ('map_embed', ''),
  ('social_linkedin', ''),
  ('social_twitter', ''),
  ('social_facebook', ''),
  ('social_instagram', '')
ON DUPLICATE KEY UPDATE `setting_value` = VALUES(`setting_value`);

INSERT INTO `services` (`slug`, `title`, `short_description`, `description`, `icon`, `sort_order`, `is_active`) VALUES
(
  'industrial-supplies',
  'Industrial Supplies',
  'MRO, spare parts, tools, and plant consumables sourced against spec.',
  'Cartalyst Global sources industrial and MRO supplies for plants, workshops, and field operations. We match technical specifications, qualify vendors, and coordinate delivery so maintenance and production teams are not waiting on parts.',
  'wrench',
  10,
  1
),
(
  'office-corporate-supplies',
  'Office & Corporate Supplies',
  'Stationery, furniture, amenities, and workplace essentials at scale.',
  'From headquarters fit-outs to multi-site office replenishment, we consolidate workplace purchasing. Clients use Cartalyst Global to standardise catalogues, control cost, and keep locations stocked without running a large internal buying team.',
  'briefcase',
  20,
  1
),
(
  'it-electronics',
  'IT & Electronics',
  'Hardware, peripherals, networking, and approved electronics procurement.',
  'We source IT hardware and electronics through vetted channels, with attention to warranty, compatibility, and shipping compliance. Typical briefs include laptops, networking gear, peripherals, and site-ready bundles for offices and projects.',
  'cpu',
  30,
  1
),
(
  'construction-materials',
  'Construction Materials',
  'Building materials, fixtures, and project packages for sites worldwide.',
  'Cartalyst Global supports contractors and developers with construction materials procurement: from bulk commodities to specified finishes. We help align lead times with project schedules and document origin, quantity, and delivery terms.',
  'hard-hat',
  40,
  1
),
(
  'medical-supplies',
  'Medical Supplies',
  'Non-pharmaceutical medical and clinical consumables with careful vendor checks.',
  'We procure medical and clinical supplies (non-pharmaceutical) for clinics, NGOs, and corporate health programmes. Vendor due diligence, documentation, and packaging requirements are handled as part of the sourcing brief.',
  'heart-pulse',
  50,
  1
),
(
  'logistics-sourcing',
  'Logistics & Sourcing',
  'Vendor discovery, consolidation, freight coordination, and door-to-site delivery.',
  'Sourcing is only complete when goods arrive. Cartalyst Global combines supplier identification with consolidation and logistics coordination so you receive one accountable partner from RFQ to delivery.',
  'truck',
  60,
  1
),
(
  'ppe-safety',
  'PPE & Safety Equipment',
  'Personal protective equipment and site safety gear to recognised standards.',
  'We source PPE and safety equipment for industrial, construction, and facility teams. Briefs typically include helmets, gloves, hi-vis, fall protection, and related consumables with standard and certification notes.',
  'shield',
  70,
  1
),
(
  'food-hospitality',
  'Food & Hospitality Supplies',
  'Kitchen, housekeeping, and hospitality consumables for operators.',
  'Hotels, caterers, and facility managers use Cartalyst Global to source hospitality supplies and consumables, consolidating vendors and aligning recurring orders with operational demand.',
  'utensils',
  80,
  1
),
(
  'energy-utilities',
  'Energy & Utilities',
  'Equipment and consumables for energy, water, and utility operations.',
  'We support energy and utility operators with procurement of specified equipment, spares, and consumables, working from datasheets and approved-manufacturer lists where required.',
  'zap',
  90,
  1
),
(
  'custom-sourcing',
  'Custom Sourcing',
  'Hard-to-find items, multi-category baskets, and tailored buying programmes.',
  'When the requirement does not fit a catalogue, Cartalyst Global runs a custom sourcing mandate: clarify the specification, identify suppliers, compare offers, and manage the order through to delivery.',
  'search',
  100,
  1
);
